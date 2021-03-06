<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IItem;

/**
 * Interface IJiraIssue
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraIssue extends IItem
{
    public const SUBJECT = 'extas.quality.crawler.jira.issue';

    public const FIELD__KEY = 'key';
    public const FIELD__FIELDS = 'fields';
    public const FIELD__ASSIGNEE = 'assignee';
    public const FIELD__WORK_LOG = 'worklog';
    public const FIELD__ISSUE_TYPE = 'issuetype';
    public const FIELD__ISSUE_LINKS = 'issuelinks';
    public const FIELD__STATUS = 'status';
    public const FIELD__CREATED = 'created';
    public const FIELD__CHANGELOG = 'changelog';

    /**
     * @return IJiraIssueChangelog
     */
    public function getChangelog(): IJiraIssueChangelog;

    /**
     * @param bool $asTimestamp
     *
     * @return string|int
     */
    public function getCreated(bool $asTimestamp = false);

    /**
     * @return IJiraStatus
     */
    public function getStatus(): IJiraStatus;

    /**
     * @return string
     */
    public function getKey(): string;

    /**
     * @return array
     */
    public function getFields(): array;

    /**
     * @return IJiraIssueLink[]
     */
    public function getIssueLinks(): array;

    /**
     * @return int
     */
    public function getBV(): int;

    /**
     * @return string
     */
    public function getAssignee(): string;

    /**
     * @return IJiraIssueType
     */
    public function getIssueType(): IJiraIssueType;

    /**
     * @return string[]
     */
    public function getTimeSpentUserNames(): array;

    /**
     * @param string $username
     *
     * @return int
     */
    public function getTimeSpent(string $username);

    /**
     * @return int
     */
    public function getReturnsCount(): int;

    /**
     * @return bool
     */
    public function isBug(): bool;

    /**
     * @param string $key
     *
     * @return IJiraIssue
     */
    public function setKey(string $key): IJiraIssue;

    /**
     * @param array $fields
     *
     * @return IJiraIssue
     */
    public function setFields(array $fields): IJiraIssue;

    /**
     * @param string $assignee
     *
     * @return IJiraIssue
     */
    public function setAssignee(string $assignee): IJiraIssue;

    /**
     * @param array $links
     *
     * @return IJiraIssue
     */
    public function setIssueLinks(array $links): IJiraIssue;

    /**
     * @param IJiraIssueType $issueType
     *
     * @return IJiraIssue
     */
    public function setIssueType(IJiraIssueType $issueType): IJiraIssue;

    /**
     * @param int $bv
     *
     * @return IJiraIssue
     */
    public function setBV(int $bv): IJiraIssue;
}
