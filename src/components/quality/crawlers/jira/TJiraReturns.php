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
            throw new \Exception(
                'Missed jira return field id.' . '\n' .
                'Please, define <info>EXTAS__Q_JIRA_RETURN_FIELD_ID</info> env parameter.'
            );
        }

        return (int) $returnFieldID;
    }
}
