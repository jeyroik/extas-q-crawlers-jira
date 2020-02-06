<?php
namespace extas\components\quality\crawlers\jira;

/**
 * Trait TJiraConfiguration
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
trait TJiraConfiguration
{
    /**
     * @return \extas\interfaces\quality\crawlers\jira\IJiraConfiguration
     * @throws \Exception
     */
    public function cfg()
    {
        return JiraConfiguration::load();
    }
}
