<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraIssueChangelogItem;

/**
 * Class JiraIssueChangelogItem
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraIssueChangelogItem extends Item implements IJiraIssueChangelogItem
{
    /**
     * @param bool $asTimestamp
     *
     * @return false|int|string
     */
    public function getCreated(bool $asTimestamp)
    {
        $created = $this->config[static::FIELD__CREATED] ?? '';

        return $asTimestamp ? strtotime($created) : $created;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
