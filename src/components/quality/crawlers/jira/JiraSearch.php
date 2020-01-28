<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraSearch;

/**
 * Class JiraSearch
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraSearch extends Item implements IJiraSearch
{
    /**
     * @return bool
     */
    public function hasItems(): bool
    {
        return (bool) ($this->config[static::FIELD__TOTAL] ?? false);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        $itemsData = $this->config[static::FIELD__ITEMS] ?? [];
        $items = [];

        foreach ($itemsData as $item) {
            $items[] = new JiraIssue($item);
        }

        return $items;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
