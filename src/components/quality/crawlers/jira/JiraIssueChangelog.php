<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraIssueChangelog;
use extas\interfaces\quality\crawlers\jira\IJiraIssueChangelogItem;

/**
 * Class JiraIssueChangelog
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraIssueChangelog extends Item implements IJiraIssueChangelog
{
    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->config[static::FIELD__TOTAL] ?? 0;
    }

    /**
     * @return bool
     */
    public function hasHistory(): bool
    {
        return $this->getTotal() > 0;
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return IJiraIssueChangelogItem|null
     */
    public function one(string $from, string $to): ?IJiraIssueChangelogItem
    {
        $history = $this->config[static::FIELD__HISTORY] ?? [];
        foreach ($history as $record) {
            $items = $record[static::FIELD__ITEMS] ?? [];
            $item = empty($items) ? [] : array_shift($items);

            $itemFrom = $item[IJiraIssueChangelogItem::FIELD__FROM] ?? '';
            $itemTo = $item[IJiraIssueChangelogItem::FIELD__TO] ?? '';

            if (($itemFrom == $from) && ($itemTo == $to)) {
                return new JiraIssueChangelogItem($record);
            }
        }

        return null;
    }

    /**
     * @param int $total
     *
     * @return IJiraIssueChangelog
     */
    public function setTotal(int $total): IJiraIssueChangelog
    {
        $this->config[static::FIELD__TOTAL] = $total;

        return $this;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
