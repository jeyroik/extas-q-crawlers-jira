<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraIssue;
use extas\interfaces\quality\crawlers\jira\IJiraIssueLink;
use extas\interfaces\quality\crawlers\jira\IJiraIssueType;

/**
 * Class JiraIssue
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraIssue extends Item implements IJiraIssue
{
    use TJiraBV;

    /**
     * @return string
     */
    public function getAssignee(): string
    {
        $fields = $this->getFields();
        $assignee = $fields[static::FIELD__ASSIGNEE] ?? [];

        return $assignee['key'] ?? '';
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getBV(): int
    {
        $fields = $this->getFields();
        $bv = $fields['customfield_' . $this->getBVId()] ?? 0;

        return (int) $bv;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->config[static::FIELD__FIELDS];
    }

    /**
     * @return IJiraIssueLink[]
     */
    public function getIssueLinks(): array
    {
        $links = [];

        $fields = $this->getFields();
        $issueLinks = $fields[static::FIELD__ISSUE_LINKS] ?? [];

        foreach ($issueLinks as $link) {
            $links[] = new JiraIssueLink($link);
        }

        return $links;
    }

    /**
     * @return IJiraIssueType
     */
    public function getIssueType(): IJiraIssueType
    {
        $fields = $this->getFields();

        $typeData = $fields[static::FIELD__ISSUE_TYPE] ?? [];

        return new JiraIssueType($typeData);
    }

    /**
     * @param string $username
     *
     * @return int
     */
    public function getTimeSpent(string $username)
    {
        $fields = $this->getFields();
        $workLog = $fields[static::FIELD__WORK_LOG] ?? [];
        $total = $workLog['total'] ?? 0;
        $spent = 0;

        if ($total) {
            $logs = $workLog['worklogs'] ?? [];
            foreach ($logs as $log) {
                if ($username == $log['author']['key']) {
                    $spent += $log['timeSpentSeconds'];
                }
            }
        }

        return $spent;
    }

    /**
     * @return array
     */
    public function getTimeSpentUserNames(): array
    {
        $fields = $this->getFields();
        $workLog = $fields[static::FIELD__WORK_LOG] ?? [];
        $total = $workLog['total'] ?? 0;
        $names = [];

        if ($total) {
            $logs = $workLog['worklogs'] ?? [];
            foreach ($logs as $log) {
                $names[] = $log['author']['key'];
            }
        }

        return $names;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->config[static::FIELD__KEY] ?? '';
    }

    /**
     * @param string $assignee
     *
     * @return IJiraIssue
     */
    public function setAssignee(string $assignee): IJiraIssue
    {
        $fields = $this->getFields();
        $fields[static::FIELD__ASSIGNEE] = $assignee;

        return $this->setFields($fields);
    }

    /**
     * @param int $bv
     *
     * @return IJiraIssue
     * @throws \Exception
     */
    public function setBV(int $bv): IJiraIssue
    {
        $fields = $this->getFields();
        $fields['customfield_' . $this->getBVId()] = $bv;

        return $this->setFields($fields);
    }

    /**
     * @param array $fields
     *
     * @return IJiraIssue
     */
    public function setFields(array $fields): IJiraIssue
    {
        $this->config[static::FIELD__FIELDS] = $fields;

        return $this;
    }

    /**
     * @param array $links
     *
     * @return IJiraIssue
     */
    public function setIssueLinks(array $links): IJiraIssue
    {
        $fields = $this->getFields();
        $fields[static::FIELD__ISSUE_LINKS] = $links;

        return $this->setFields($fields);
    }

    /**
     * @param IJiraIssueType $issueType
     *
     * @return IJiraIssue
     */
    public function setIssueType(IJiraIssueType $issueType): IJiraIssue
    {
        $fields = $this->getFields();
        $fields[static::FIELD__ISSUE_TYPE] = $issueType;

        return $this->setFields($fields);
    }

    /**
     * @param string $key
     *
     * @return IJiraIssue
     */
    public function setKey(string $key): IJiraIssue
    {
        $this->config[static::FIELD__KEY] = $key;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBug(): bool
    {
        $bug = getenv('EXTAS__Q_JIRA_BUG') ?: 'Bug';
        $bug = (strpos($bug, ',') !== false)
            ? $bug = explode(',', $bug)
            : $bug = [$bug];

        $type = $this->getIssueType();

        return in_array($type->getName(), $bug);
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
