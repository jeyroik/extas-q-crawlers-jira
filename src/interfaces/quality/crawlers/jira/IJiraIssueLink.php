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
    const SUBJECT = 'extas.quality.crawler.jira.issue.link';

    const FIELD__TYPE = 'type';
    const FIELD__ISSUE_ID = 'id';
    const FIELD__ISSUE_LINKS_OUTWARD = 'outwardIssue';

    const IS__OUTWARD = 'outwardIssue';
    const IS__INWARD = 'inwardIssue';

    const TYPE__PARENT = 'Parent';

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
