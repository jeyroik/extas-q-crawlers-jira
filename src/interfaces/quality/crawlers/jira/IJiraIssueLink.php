<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IItem;

/**
 * Interface IJiraIssueLink
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraIssueLink extends IItem
{
    public const SUBJECT = 'extas.quality.crawler.jira.issue.link';

    public const FIELD__TYPE = 'type';
    public const FIELD__ISSUE_ID = 'id';
    public const FIELD__ISSUE_LINKS_OUTWARD = 'outwardIssue';

    public const IS__OUTWARD = 'outwardIssue';
    public const IS__INWARD = 'inwardIssue';

    public const TYPE__PARENT = 'Parent';

    /**
     * @return bool
     */
    public function isChild(): bool;

    /**
     * @return bool
     */
    public function isParent(): bool;

    /**
     * @param string $linkDirection
     *
     * @return string
     */
    public function getIssueKey(string $linkDirection = self::IS__OUTWARD): string;
}
