<?php
namespace extas\components\quality\crawlers\jira;

/**
 * Trait TJiraBV
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
trait TJiraBV
{
    /**
     * @return int
     * @throws \Exception
     */
    protected function getBVId(): int
    {
        $bvFieldID = JiraConfiguration::load()->getBVFieldId();

        if (!$bvFieldID) {
            throw new \Exception(
                'Missed jira bv field id.' . '\n' .
                'Please, define <info>EXTAS__Q_JIRA_BV_FIELD_ID</info> env parameter.'
            );
        }

        return (int) $bvFieldID;
    }
}
