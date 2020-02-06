<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IItem;

/**
 * Interface IJiraIssueChangelog
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraIssueChangelog extends IItem
{
    const SUBJECT = 'extas.quality.crawler.jira.issue.changelog';

    const FIELD__TOTAL = 'total';
    const FIELD__HISTORY = 'histories';
    const FIELD__ITEMS = 'items';

    /**
     * @param string $from
     * @param string $to
     *
     * @return IJiraIssueChangelogItem|null
     */
    public function one(string $from, string $to): ?IJiraIssueChangelogItem;

    /**
     * @return int
     */
    public function getTotal(): int;

    /**
     * @return bool
     */
    public function hasHistory(): bool;

    /**
     * @param int $total
     *
     * @return IJiraIssueChangelog
     */
    public function setTotal(int $total): IJiraIssueChangelog;
}
