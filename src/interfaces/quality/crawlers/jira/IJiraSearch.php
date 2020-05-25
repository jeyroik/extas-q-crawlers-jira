<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IItem;

/**
 * Interface IJiraSearch
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraSearch extends IItem
{
    public const SUBJECT = 'extas.quality.crawler.jira.search';

    public const FIELD__TOTAL = 'total';
    public const FIELD__ITEMS = 'issues';

    /**
     * @return bool
     */
    public function hasItems(): bool;

    /**
     * @return IJiraIssue[]
     */
    public function getItems(): array;
}
