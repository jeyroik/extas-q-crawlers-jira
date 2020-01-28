<?php
namespace extas\components\quality\crawlers;

use extas\components\quality\crawlers\jira\JiraClient;
use extas\components\quality\users\User;
use extas\components\SystemContainer;
use extas\interfaces\quality\crawlers\ICrawler;
use extas\interfaces\quality\crawlers\jira\IJiraIssue;
use extas\interfaces\quality\crawlers\jira\IJiraIssueLink;
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
            foreach ($jiraClient->allStories() as $story) {
                $this->story2BVAndKeys($story, $bvs, $keys);
            }
            $assignees = [];
            foreach ($jiraClient->allTickets($keys) as $ticket) {
                $this->ticketToAssignees($ticket, $assignees, $bvs);
            }
        } catch (\Exception $e) {
            $messages = explode('\n', $e->getMessage());
            $output->writeln($messages);
            return $this;
        }

        /**
         * @var $userRepo IUserRepository
         * @var $users IUser[]
         */
        $userRepo = SystemContainer::getItem(IUserRepository::class);
        $users = $userRepo->all([IUser::FIELD__NAME => array_keys($assignees)]);
        $usersByNames = [];
        foreach ($users as $user) {
            $usersByNames[$user->getName()] = true;
            $user = $this->applyNewData($user, $assignees);
            $userRepo->update($user);
        }
        
        foreach ($assignees as $userName => $userData) {
            if (isset($usersByNames[$userName])) {
                continue;
            }

            $user = new User([User::FIELD__NAME => $userName]);
            $user = $this->applyNewData($user, $assignees);
            $userRepo->create($user);
        }
        return $this;
    }

    /**
     * @param IUser $user
     * @param array $assignees
     *
     * @return IUser
     */
    protected function applyNewData(IUser $user, array $assignees)
    {
        $user->setIssuesBVSum($assignees[$user->getName()]['sum'])
            ->setIssuesDoneSum($assignees[$user->getName()]['done'])
            ->setTimeSpentSum($assignees[$user->getName()]['time_spent'])
            ->setBugsSum($assignees[$user->getName()]['bugs']);

        return $user;
    }

    /**
     * @param IJiraIssue $story
     * @param array $bvs
     * @param array $keys
     */
    protected function story2BVAndKeys(IJiraIssue $story, array &$bvs, array &$keys)
    {
        $bvs[$story->getKey()] = $story->getBV();
        foreach ($story->getIssueLinks() as $link) {
            /**
             * @var $link IJiraIssueLink
             */
            if ($link->isParent()) {
                $keys[] = $link->getIssueKey();
            }
        }
    }

    /**
     * @param IJiraIssue $ticket
     * @param array $assignees
     * @param array $bvs
     */
    protected function ticketToAssignees(IJiraIssue $ticket, array &$assignees, array $bvs)
    {
        foreach ($ticket->getIssueLinks() as $link) {
            /**
             * @var $link IJiraIssueLink
             */
            $issueKey = $link->getIssueKey();
            if ($link->isChild() && isset($bvs[$issueKey])) {
                $users = $ticket->getTimeSpentUserNames();
                foreach ($users as $user) {
                    if (!isset($assignees[$user])) {
                        $assignees[$user] = [
                            'sum' => 0,
                            'time_spent' => 0,
                            'bugs' => 0,
                            'done' => 0,
                            'index' => []
                        ];
                    }
                    if (!isset($assignees[$user]['index'][$issueKey])) {
                        $assignees[$user]['sum'] += $bvs[$issueKey];
                        $assignees[$user]['time_spent'] += $ticket->getTimeSpent($user);
                        $assignees[$user]['done']++;
                        $assignees[$user]['index'][$issueKey] = true;

                        if ($ticket->isBug()) {
                            $assignees[$user]['bugs']++;
                        }
                    }
                }
            }
        }
    }
}
