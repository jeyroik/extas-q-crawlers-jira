<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IItem;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface IJiraSearchJQL
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraSearchJQL extends IItem
{
    const SUBJECT = 'extas.quality.crawler.jira.jql';

    const PARAM__ISSUE_TYPE = 'issuetype';
    const PARAM__ISSUE_LINK_TYPE = 'issuelinktype';
    const PARAM__ISSUE_KEY = 'key';
    const PARAM__ISSUE_LINKS = 'issuelinks';
    const PARAM__ASSIGNEE = 'assignee';
    const PARAM__WORK_LOG = 'worklog';
    const PARAM__ISSUE_BV = '@bv';
    const PARAM__FIELDS = 'fields';
    const PARAM__UPDATED_DATE = 'updatedDate';
    const PARAM__PROJECT_KEY = 'project';

    const ISSUE_TYPE__STORY = 'story';
    const ISSUE_TYPE__BUG = 'bug';

    const LINK_TYPE__PARENT = 'is Parent of';

    const DATE__END_OF_MONTH = 'endOfMonth';

    const CONDITION__GREATER = '>';
    const CONDITION__LOWER = '<';

    const URI = 'uri';

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
     * @return string
     */
    public function build(): string;

    /**
     * @return array
     */
    public function buildJson(): array;
}
