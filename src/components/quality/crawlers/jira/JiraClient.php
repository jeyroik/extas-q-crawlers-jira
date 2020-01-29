<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraClient;
use extas\components\quality\crawlers\jira\JiraSearchJQL as JQL;
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
        $client = $this->getHttClient();
        $jql->issueType([JQL::ISSUE_TYPE__STORY])
            ->issueLinkType([JQL::LINK_TYPE__PARENT])
            ->bv(JQL::CONDITION__GREATER, 0)
            ->updatedDate(JQL::CONDITION__LOWER, JQL::DATE_FUNC__END_OF_MONTH, -1)
            ->returnFields([
                JQL::PARAM__ISSUE_LINKS,
                JQL::PARAM__ISSUE_BV
            ]);

        $response = $client->get($jql->build(), [
            'auth' => [
                getenv('EXTAS__Q_JIRA_LOGIN') ?: '',
                getenv('EXTAS__Q_JIRA_PASSWORD') ?: ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $search = new JiraSearch($data);
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
        $client = $this->getHttClient();
        $jql->issueKey($keys)
            ->returnFields([
                JQL::PARAM__ISSUE_LINKS,
                JQL::PARAM__ASSIGNEE,
                JQL::PARAM__WORK_LOG
            ]);

        $response = $client->get($jql->build(), [
            'auth' => [
                getenv('EXTAS__Q_JIRA_LOGIN') ?: '',
                getenv('EXTAS__Q_JIRA_PASSWORD') ?: ''
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $search = new JiraSearch($data);
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
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
