<?php
namespace extas\components\quality\crawlers;

use extas\components\quality\crawlers\jira\indexes\JiraIssuesIndex;
use extas\components\quality\crawlers\jira\JiraClient;
use extas\components\quality\users\User;
use extas\components\SystemContainer;
use extas\interfaces\quality\crawlers\ICrawler;
use extas\interfaces\quality\crawlers\jira\IJiraIssue;
use extas\interfaces\quality\crawlers\jira\IJiraIssueLink;
use extas\interfaces\quality\crawlers\jira\indexes\IJIraIssuesIndex;
use extas\interfaces\quality\crawlers\jira\indexes\IJiraIssuesIndexRepository;
use extas\interfaces\quality\users\IUser;
use extas\interfaces\quality\users\IUserRepository;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CrawlerJira
 *
 * @package extas\components\quality\crawlers
 * @author jeyroik@gmail.com
 */
class CrawlerJira extends Crawler
{
    /**
     * @var IJIraIssuesIndex
     */
    protected $index = null;

    /**
     * CrawlerJira constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->index = $this->getIndex();
    }

    /**
     * @param OutputInterface $output
     *
     * @return ICrawler
     */
    public function crawl(OutputInterface &$output): ICrawler
    {
        try {
            $jiraClient = new JiraClient();
            $keys = [];
            $bvs = [];
            $foundStories = 0;
            foreach ($jiraClient->allStories() as $story) {
                /**
                 * @var $story IJiraIssue
                 */
                $output->writeln(['Operating story <info>' . $story->getKey() . '</info>']);
                $this->story2BVAndKeys($story, $bvs, $keys, $output);
                $foundStories++;
            }
            if (!$foundStories) {
                $output->writeln(['<info>There are no applicable stories yet.</info>']);
            }
            $assignees = [];
            $tickets = 0;
            foreach ($jiraClient->allTickets($keys) as $ticket) {
                /**
                 * @var $ticket IJiraIssue
                 */
                $output->writeln(['Operating ticket <info>' . $ticket->getKey() . '</info>']);
                $this->ticketToAssignees($ticket, $assignees, $bvs, $output);
                $tickets++;
            }
            if (!$tickets) {
                $output->writeln(['<info>There are no applicable tickets yet.</info>']);
            }
        } catch (\Exception $e) {
            $messages = explode('\n', $e->getMessage());
            $output->writeln($messages);
            return $this;
        }

        $this->index->commit();
        $this->updateUsersInfo($assignees, $output);

        return $this;
    }

    /**
     * @param array $assignees
     * @param OutputInterface $output
     */
    protected function updateUsersInfo(array $assignees, OutputInterface &$output)
    {
        /**
         * @var $userRepo IUserRepository
         * @var $users IUser[]
         */
        $userRepo = SystemContainer::getItem(IUserRepository::class);
        $users = $userRepo->all([IUser::FIELD__NAME => array_keys($assignees)]);
        $usersByNames = [];
        foreach ($users as $user) {
            $usersByNames[$user->getName()] = true;
            $user = $this->applyNewData($user, $assignees, $output);
            $userRepo->update($user);
            $output->writeln(['Update user <info>' . $user->getName() . '</info>']);
        }

        foreach ($assignees as $userName => $userData) {
            if (isset($usersByNames[$userName])) {
                continue;
            }

            $user = new User([User::FIELD__NAME => $userName]);
            $user = $this->applyNewData($user, $assignees, $output);
            $userRepo->create($user);
            $output->writeln(['Create user <info>' . $user->getName() . '</info>']);
        }
    }

    /**
     * @return IJIraIssuesIndex|null
     */
    protected function getIndex(): ?IJIraIssuesIndex
    {
        /**
         * @var $repo IJiraIssuesIndexRepository
         */
        $repo = SystemContainer::getItem(IJiraIssuesIndexRepository::class);
        $index = $repo->one([IJIraIssuesIndex::FIELD__MONTH => date('Ym')]);

        if (!$index) {
            $index = new JiraIssuesIndex([
                JiraIssuesIndex::FIELD__MONTH => date('Ym'),
                JiraIssuesIndex::FIELD__TIMESTAMP => time(),
                JiraIssuesIndex::FIELD__ISSUES => []
            ]);
            $repo->create($index);
        }

        return $index;
    }

    /**
     * @param IUser $user
     * @param array $assignees
     * @param OutputInterface $output
     *
     * @return IUser
     */
    protected function applyNewData(IUser $user, array $assignees, OutputInterface &$output)
    {
        $userData = $assignees[$user->getName()];

        $user->setIssuesBVSum($userData['sum'])
            ->setIssuesDoneSum($userData['done'])
            ->setTimeSpentSum($userData['time_spent'])
            ->setBugsSum($userData['bugs'])
            ->setIssuesReturnsCount($userData['returns']);

        foreach ($this->getPluginsByStage('quality.user.data.applied') as $plugin) {
            $plugin($user, $output);
        }

        return $user;
    }

    /**
     * @param IJiraIssue $story
     * @param array $bvs
     * @param array $keys
     * @param OutputInterface $output
     */
    protected function story2BVAndKeys(IJiraIssue $story, array &$bvs, array &$keys, OutputInterface $output)
    {
        $bvs[$story->getKey()] = $story->getBV();
        foreach ($story->getIssueLinks() as $link) {
            /**
             * @var $link IJiraIssueLink
             */
            if ($link->isParent()) {
                if ($this->index->hasIssue($link->getIssueKey())) {
                    $output->writeln([
                        '<comment>Child ticket ' . $link->getIssueKey() . ' is already operated.</comment>',
                        '<comment>Skipped it.</comment>'
                    ]);
                    continue;
                }
                $keys[] = $link->getIssueKey();
            }
        }
    }

    /**
     * @param IJiraIssue $ticket
     * @param array $assignees
     * @param array $bvs
     * @param OutputInterface $output
     */
    protected function ticketToAssignees(IJiraIssue $ticket, array &$assignees, array $bvs, OutputInterface $output)
    {
        if ($ticket->getStatus()->isDone()) {
            foreach ($ticket->getIssueLinks() as $link) {
                /**
                 * @var $link IJiraIssueLink
                 */
                $issueKey = $link->getIssueKey(IJiraIssueLink::IS__INWARD);
                if ($link->isChild() && isset($bvs[$issueKey])) {
                    $this->index->addIssue($ticket);
                    $users = $ticket->getTimeSpentUserNames();
                    foreach ($users as $user) {
                        if (!isset($assignees[$user])) {
                            $assignees[$user] = [
                                'sum' => 0,
                                'time_spent' => 0,
                                'bugs' => 0,
                                'returns' => 0,
                                'done' => 0,
                                'index' => []
                            ];
                        }
                        if (!isset($assignees[$user]['index'][$issueKey])) {
                            $assignees[$user]['sum'] += $bvs[$issueKey];
                            $assignees[$user]['time_spent'] += $ticket->getTimeSpent($user);
                            $assignees[$user]['returns'] += $ticket->getReturnsCount();
                            $assignees[$user]['done']++;
                            $assignees[$user]['index'][$issueKey] = true;

                            if ($ticket->isBug()) {
                                $assignees[$user]['bugs']++;
                            }
                        }
                    }
                }
            }
        } else {
            $output->writeln([
                '<comment>Ticket is not done yet</comment>',
                '<comment>Current state is "' . $ticket->getStatus()->getCategoryName() . '"</comment>'
            ]);
        }
    }
}
