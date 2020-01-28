<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraIssueLink;
use extas\interfaces\quality\crawlers\jira\IJiraSearchJQL;

/**
 * Class JiraIssueLink
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraIssueLink extends Item implements IJiraIssueLink
{
    /**
     * @return string
     */
    public function getIssueKey(): string
    {
        $issue = $this->config[static::FIELD__ISSUE_LINKS_OUTWARD] ?? [];

        return $issue[IJiraSearchJQL::PARAM__ISSUE_KEY] ?? '';
    }

    /**
     * @return bool
     */
    public function isParent(): bool
    {
        $type = $this->getType();

        return $type['name'] == static::TYPE__PARENT;
    }

    /**
     * @return bool
     */
    public function isChild(): bool
    {
        $type = $this->getType();

        return $type['name'] == static::TYPE__CHILD;
    }

    /**
     * @return array
     */
    protected function getType(): array
    {
        return $this->config[static::FIELD__TYPE] ?? ['name' => ''];
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
