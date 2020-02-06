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
    use TJiraConfiguration;

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
     * @param array $returnFields
     *
     * @return array|\Generator
     * @throws
     */
    public function allStories(array $returnFields = [])
    {
        $returnFields = $this->mergeReturnFields($returnFields, [JQL::PARAM__ISSUE_LINKS]);
        $jql = new JQL();
        $jql->issueType([JQL::ISSUE_TYPE__STORY])
            ->issueLinkType([JQL::LINK_TYPE__PARENT])
            ->bv(JQL::CONDITION__GREATER, 0)
            ->updatedDate(JQL::CONDITION__LOWER, JQL::DATE_FUNC__END_OF_MONTH, -1)
            ->returnFields($returnFields);

        if ($projectKeys = $this->getProjectKeys()) {
            $jql->projectKey($projectKeys);
        }

        $search = new JiraSearch($this->getResponse($jql));
        $items = $search->hasItems() ? $search->getItems() : [];

        foreach ($items as $item) {
            yield $item;
        }
    }

    /**
     * @param array $keys
     * @param array $returnFields
     *
     * @return \Generator
     * @throws
     */
    public function allTickets(array $keys, array $returnFields = [])
    {
        $returnFieldsDefault = [
            JQL::PARAM__ISSUE_LINKS,
            JQL::PARAM__ASSIGNEE,
            JQL::PARAM__WORK_LOG
        ];

        $returnFields = $this->mergeReturnFields($returnFields, $returnFieldsDefault);

        $jql = new JQL();
        $jql->issueKey($keys)
            ->returnFields($returnFields);

        if ($projectKeys = $this->getProjectKeys()) {
            $jql->projectKey($projectKeys);
        }

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
     * @return string[]
     */
    public function getProjectKeys(): array
    {
        return $this->config[IJiraSearchJQL::PARAM__PROJECT_KEY] ?? [];
    }

    /**
     * @param string[] $keys
     *
     * @return IJiraClient
     */
    public function setProjectKeys(array $keys): IJiraClient
    {
        $this->config[IJiraSearchJQL::PARAM__PROJECT_KEY] = $keys;

        return $this;
    }

    /**
     * @param array $current
     * @param array $default
     *
     * @return array
     */
    protected function mergeReturnFields(array $current, array $default): array
    {
        foreach ($default as $field) {
            if (!in_array($field, $current)) {
                $current[] = $field;
            }
        }

        return $current;
    }

    /**
     * @param IJiraSearchJQL $jql
     *
     * @return array
     * @throws \Exception
     */
    protected function getResponse(IJiraSearchJQL $jql)
    {
        $config = $this->cfg();
        $isWrapped = $config->getIsWrapped();

        if ($isWrapped) {
            return $this->getWrappedResponse($jql);
        } else {
            $client = $this->getHttClient();
            $response = $client->get($jql->build(), [
                'auth' => [
                    $config->getLogin(),
                    $config->getPassword()
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return $data;
        }
    }

    /**
     * @param IJiraSearchJQL $jql
     *
     * @return array|mixed
     * @throws \Exception
     */
    protected function getWrappedResponse(IJiraSearchJQL $jql)
    {
        $client = $this->getHttClient();
        $response = $client->get(
            $this->cfg()->getEndpoint(),
            ['json' => $jql->buildJson()]
        );

        $data = json_decode($response->getBody(), true);
        $data = $data['data'] ?? [];
        $data = $data['proxy-response'] ?? [];

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
