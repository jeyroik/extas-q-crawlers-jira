<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraIssue;
use extas\interfaces\quality\crawlers\jira\IJiraSearchJQL;

/**
 * Class JiraSearchJQL
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraSearchJQL extends Item implements IJiraSearchJQL
{
    use TJiraBV;
    use TJiraReturns;
    use TJiraConfiguration;

    protected bool $jqlStarted = false;

    /**
     * JiraSearchJQL constructor.
     *
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        $this->initUri();

        parent::__construct($config);
    }

    /**
     * @param string $condition
     * @param int $value
     *
     * @return IJiraSearchJQL
     * @throws \Exception
     */
    public function bv(string $condition, int $value): IJiraSearchJQL
    {
        $this->appendToUri(
            'cf[' . $this->getBVId() . '] ' . $condition . ' ' . $value
        );

        return $this;
    }

    /**
     * @param int $limit
     *
     * @return IJiraSearchJQL
     */
    public function limit(int $limit): IJiraSearchJQL
    {
        $this->config[static::FIELD__LIMIT] = $limit;

        return $this;
    }

    /**
     * @param string $condition
     * @param string $dateFunction
     * @param string $dateFunctionArgument
     *
     * @return IJiraSearchJQL
     */
    public function updatedDate(string $condition, string $dateFunction, string $dateFunctionArgument): IJiraSearchJQL
    {
        $this->appendToUri(
            static::PARAM__UPDATED_DATE . ' ' . $condition . ' ' .
            $dateFunction . '(' . $dateFunctionArgument . ')'
        );

        return $this;
    }

    /**
     * @param string[] $keys
     *
     * @return IJiraSearchJQL
     */
    public function issueKey(array $keys): IJiraSearchJQL
    {
        $this->appendToUri(static::PARAM__ISSUE_KEY . ' in ("' . implode('","', $keys) . '")');

        return $this;
    }

    /**
     * @param string[] $keys
     *
     * @return IJiraSearchJQL
     */
    public function projectKey(array $keys): IJiraSearchJQL
    {
        $this->appendToUri(static::PARAM__PROJECT_KEY . ' in ("' . implode('","', $keys) . '")');

        return $this;
    }

    /**
     * @param string[] $types
     *
     * @return IJiraSearchJQL
     */
    public function issueLinkType(array $types): IJiraSearchJQL
    {
        $this->appendToUri(static::PARAM__ISSUE_LINK_TYPE . ' in ("' . implode('","', $types) . '")');

        return $this;
    }

    /**
     * @param string[] $types
     *
     * @return IJiraSearchJQL
     */
    public function issueType(array $types): IJiraSearchJQL
    {
        $this->appendToUri(static::PARAM__ISSUE_TYPE . ' in ("' . implode('","', $types) . '")');

        return $this;
    }

    /**
     * @param array $fields
     *
     * @return IJiraSearchJQL
     * @throws
     */
    public function returnFields(array $fields): IJiraSearchJQL
    {
        $fields[] = 'customfield_' . $this->getBVId();
        $fields[] = 'customfield_' . $this->getReturnsId();
        $fields[] = IJiraIssue::FIELD__STATUS;
        $this->config[static::FIELD__URI] .= '&' . static::PARAM__FIELDS . '=' . implode(',', $fields);

        return $this;
    }

    /**
     * @param array $expands
     *
     * @return IJiraSearchJQL
     */
    public function expand(array $expands): IJiraSearchJQL
    {
        $this->config[static::FIELD__EXPAND] = $expands;

        return $this;
    }

    /**
     * @return string
     * @throws
     */
    public function build(): string
    {
        $built = $this->cfg()->getEndpoint() . $this->cfg()->getEndpointVersion() .
            'search?jql=' . $this->config[static::FIELD__URI];

        $expand = $this->config[static::FIELD__EXPAND] ?? [];

        if ($expand) {
            $built .= '&expand=' . implode(',', $expand);
        }

        $limit = $this->config[static::FIELD__LIMIT] ?? 0;

        if ($limit) {
            $built .= '&' . static::FIELD__LIMIT . '=' . $limit;
        }

        return $built;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function buildJson(): array
    {
        $token = $this->cfg()->getWrapperToken();

        if (!$token) {
            throw new \Exception('Missed jira wrapper token.');
        }

        $built = 'search?jql='.$this->config[static::FIELD__URI];
        $expand = $this->config[static::FIELD__EXPAND] ?? [];

        if ($expand) {
            $built .= '&expand=' . implode(',', $expand);
        }

        $limit = $this->config[static::FIELD__LIMIT] ?? 0;

        if ($limit) {
            $built .= '&' . static::FIELD__LIMIT . '=' . $limit;
        }

        return [
            'token' => $token,
            'version' => '1.0',
            'action' => 'service:proxy',
            'data' => [
                'service' => [
                    'name' => 'jira',
                    'query' => $built
                ]
            ]
        ];
    }

    /**
     * @param string $uriPath
     *
     * @return IJiraSearchJQL
     */
    protected function appendToUri(string $uriPath): IJiraSearchJQL
    {
        if ($this->jqlStarted) {
            $this->config[static::FIELD__URI] .= ' and ';
        } else {
            $this->jqlStarted = true;
        }

        $this->config[static::FIELD__URI] .= $uriPath;

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function initUri()
    {
        $this->config[static::FIELD__URI] = '';
        return $this;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
