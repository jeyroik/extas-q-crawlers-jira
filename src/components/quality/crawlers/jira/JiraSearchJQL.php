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

    protected $jqlStarted = false;

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
        $this->config[static::URI] .= '&' . static::PARAM__FIELDS . '=' . implode(',', $fields);

        return $this;
    }

    /**
     * @return string
     * @throws
     */
    public function build(): string
    {
        return $this->cfg()->getEndpoint() . $this->cfg()->getEndpointVersion() .
            'search?jql=' . $this->config[static::URI];
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

        return [
            'token' => $token,
            'version' => '1.0',
            'action' => 'service:proxy',
            'data' => [
                'service' => [
                    'name' => 'jira',
                    'query' => 'search?jql='.$this->config[static::URI]
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
            $this->config[static::URI] .= ' and ';
        } else {
            $this->jqlStarted = true;
        }

        $this->config[static::URI] .= $uriPath;

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    protected function initUri()
    {
        $this->config[static::URI] = '';
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
