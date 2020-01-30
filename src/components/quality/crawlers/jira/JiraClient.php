<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraClient;
use extas\components\quality\crawlers\jira\JiraSearchJQL as JQL;
use extas\interfaces\quality\crawlers\jira\IJiraSearchJQL;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * Class JiraClient
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraClient extends Item implements IJiraClient
{
    /**
     * JiraClient constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $config[static::FIELD__HTTP_CLIENT] = $config[static::FIELD__HTTP_CLIENT] ?? new Client();

        parent::__construct($config);
    }

    /**
     * @return array|\Generator
     * @throws
     */
    public function allStories()
    {
        $jql = new JQL();
        $jql->issueType([JQL::ISSUE_TYPE__STORY])
            ->issueLinkType([JQL::LINK_TYPE__PARENT])
            ->bv(JQL::CONDITION__GREATER, 0)
            ->updatedDate(JQL::CONDITION__LOWER, JQL::DATE_FUNC__END_OF_MONTH, -1)
            ->returnFields([
                JQL::PARAM__ISSUE_LINKS,
                JQL::PARAM__ISSUE_BV
            ]);
        $search = new JiraSearch($this->getResponse($jql));
        $items = $search->hasItems() ? $search->getItems() : [];

        foreach ($items as $item) {
            yield $item;
        }
    }

    /**
     * @param array $keys
     *
     * @return \Generator
     * @throws
     */
    public function allTickets(array $keys)
    {
        $jql = new JQL();
        $jql->issueKey($keys)
            ->returnFields([
                JQL::PARAM__ISSUE_LINKS,
                JQL::PARAM__ASSIGNEE,
                JQL::PARAM__WORK_LOG
            ]);

        $search = new JiraSearch($this->getResponse($jql));
        $items = $search->hasItems() ? $search->getItems() : [];
        foreach ($items as $item) {
            yield $item;
        }
    }

    /**
     * @return ClientInterface|Client
     */
    public function getHttClient()
    {
        return $this->config[static::FIELD__HTTP_CLIENT] ?? null;
    }

    /**
     * @param IJiraSearchJQL $jql
     *
     * @return array
     */
    protected function getResponse(IJiraSearchJQL $jql)
    {
        $client = $this->getHttClient();
        $response = $client->get($jql->build(), [
            'auth' => [
                getenv('EXTAS__Q_JIRA_LOGIN') ?: '',
                getenv('EXTAS__Q_JIRA_PASSWORD') ?: ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $isWrapped = getenv('EXTAS__Q_JIRA_WRAPPED') ?: 0;

        if ($isWrapped) {
            $data = $data['data'] ?? [];
            $data = $data['proxy-response'] ?? [];
        }

        return $data;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
