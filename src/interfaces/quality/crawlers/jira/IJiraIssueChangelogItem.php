<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IItem;

/**
 * Interface IJiraIssueChangelogItem
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraIssueChangelogItem extends IItem
{
    public const SUBJECT = 'extas.quality.crawler.jira.issue.changelog.item';

    public const FIELD__CREATED = 'created';
    public const FIELD__FROM = 'fromString';
    public const FIELD__TO = 'toString';

    /**
     * @param bool $asTimestamp
     *
     * @return string|int
     */
    public function getCreated(bool $asTimestamp);
}
