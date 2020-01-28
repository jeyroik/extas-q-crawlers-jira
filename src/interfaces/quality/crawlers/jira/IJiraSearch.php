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
    const SUBJECT = 'extas.quality.crawler.jira.search';

    const FIELD__TOTAL = 'total';
    const FIELD__ITEMS = 'items';

    /**
     * @return bool
     */
    public function hasItems(): bool;

    /**
     * @return IJiraIssue[]
     */
    public function getItems(): array;
}
