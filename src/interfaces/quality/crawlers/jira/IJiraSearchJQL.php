<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IItem;

/**
 * Interface IJiraSearchJQL
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraSearchJQL extends IItem
{
    public const SUBJECT = 'extas.quality.crawler.jira.jql';

    public const FIELD__EXPAND = 'expand';
    public const FIELD__URI = 'uri';
    public const FIELD__LIMIT = 'maxResults';

    public const PARAM__ISSUE_TYPE = 'issuetype';
    public const PARAM__ISSUE_LINK_TYPE = 'issuelinktype';
    public const PARAM__ISSUE_KEY = 'key';
    public const PARAM__ISSUE_LINKS = 'issuelinks';
    public const PARAM__ASSIGNEE = 'assignee';
    public const PARAM__WORK_LOG = 'worklog';
    public const PARAM__ISSUE_BV = '@bv';
    public const PARAM__FIELDS = 'fields';
    public const PARAM__UPDATED_DATE = 'updatedDate';
    public const PARAM__PROJECT_KEY = 'project';

    public const ISSUE_TYPE__STORY = 'story';
    public const ISSUE_TYPE__BUG = 'bug';

    public const LINK_TYPE__PARENT = 'is Parent of';

    public const DATE__START_OF_MONTH = 'startOfMonth';
    public const DATE__END_OF_MONTH = 'endOfMonth';

    public const CONDITION__GREATER = '>';
    public const CONDITION__LOWER = '<';

    /**
     * @param int $limit
     *
     * @return IJiraSearchJQL
     */
    public function limit(int $limit): IJiraSearchJQL;

    /**
     * @param string $condition
     * @param string $dateFunction
     * @param string $dateFunctionArgument
     * 
     * @return IJiraSearchJQL
     */
    public function updatedDate(string $condition, string $dateFunction, string $dateFunctionArgument): IJiraSearchJQL;
    
    /**
     * @param string $condition
     * @param int $value
     * 
     * @return IJiraSearchJQL
     */
    public function bv(string $condition, int $value): IJiraSearchJQL;

    /**
     * @param array $types
     *
     * @return IJiraSearchJQL
     */
    public function issueType(array $types): IJiraSearchJQL;

    /**
     * @param array $types
     *
     * @return IJiraSearchJQL
     */
    public function issueLinkType(array $types): IJiraSearchJQL;

    /**
     * @param array $fields
     *
     * @return IJiraSearchJQL
     */
    public function returnFields(array $fields): IJiraSearchJQL;

    /**
     * @param array $keys
     *
     * @return IJiraSearchJQL
     */
    public function issueKey(array $keys): IJiraSearchJQL;

    /**
     * @param array $keys
     *
     * @return IJiraSearchJQL
     */
    public function projectKey(array $keys): IJiraSearchJQL;

    /**
     * @param array $expands
     *
     * @return IJiraSearchJQL
     */
    public function expand(array $expands): IJiraSearchJQL;

    /**
     * @return string
     */
    public function build(): string;

    /**
     * @return array
     */
    public function buildJson(): array;
}
