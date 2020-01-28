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

    const TYPE__PARENT = 'Parent';
    const TYPE__CHILD = 'Child';

    /**
     * @return bool
     */
    public function isChild(): bool;

    /**
     * @return bool
     */
    public function isParent(): bool;

    /**
     * @return string
     */
    public function getIssueKey(): string;
}
