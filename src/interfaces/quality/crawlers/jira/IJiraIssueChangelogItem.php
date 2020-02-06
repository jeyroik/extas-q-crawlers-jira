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
    const SUBJECT = 'extas.quality.crawler.jira.issue.changelog.item';

    const FIELD__CREATED = 'created';
    const FIELD__FROM = 'fromString';
    const FIELD__TO = 'toString';

    /**
     * @param bool $asTimestamp
     *
     * @return string|int
     */
    public function getCreated(bool $asTimestamp);
}
