<?php
namespace extas\interfaces\quality\crawlers\jira\indexes;

use extas\interfaces\IItem;
use extas\interfaces\quality\crawlers\jira\IJiraIssue;

/**
 * Interface IJIraIssuesIndex
 * 
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJIraIssuesIndex extends IItem
{
    const SUBJECT = 'extas.quality.crawler.jira.issues.index';
    
    const FIELD__MONTH = 'month';
    const FIELD__ISSUES = 'issues';
    const FIELD__TIMESTAMP = 'timestamp';

    /**
     * @return int
     */
    public function getMonth(): int;

    /**
     * @return int
     */
    public function getTimestamp(): int;

    /**
     * @return array
     */
    public function getIssues(): array;

    /**
     * @param string $issueKey
     *
     * @return bool
     */
    public function hasIssue(string $issueKey): bool;

    /**
     * @param int $month
     * 
     * @return IJIraIssuesIndex
     */
    public function setMonth(int $month): IJIraIssuesIndex;

    /**
     * @param int $timestamp
     * 
     * @return IJIraIssuesIndex
     */
    public function setTimestamp(int $timestamp): IJIraIssuesIndex;

    /**
     * @param array $items
     * 
     * @return IJIraIssuesIndex
     */
    public function setIssues(array $items): IJIraIssuesIndex;

    /**
     * @param IJiraIssue $issue
     *
     * @return IJIraIssuesIndex
     */
    public function addIssue(IJiraIssue $issue): IJIraIssuesIndex;

    /**
     * @return IJIraIssuesIndex
     */
    public function commit(): IJIraIssuesIndex;
}