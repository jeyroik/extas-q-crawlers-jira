<?php
namespace extas\components\quality\crawlers\jira;

/**
 * Trait TJiraReturns
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
trait TJiraReturns
{
    /**
     * @return int
     * @throws \Exception
     */
    protected function getReturnsId(): int
    {
        $returnFieldID = JiraConfiguration::load()->getReturnFieldId();

        if (!$returnFieldID) {
            throw new \Exception('Missed jira return field id.');
        }

        return (int) $returnFieldID;
    }
}
