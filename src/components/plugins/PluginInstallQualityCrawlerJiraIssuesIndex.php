<?php
namespace extas\components\plugins;

use extas\components\quality\crawlers\jira\indexes\JiraIssuesIndex;
use extas\interfaces\quality\crawlers\jira\indexes\IJiraIssuesIndexRepository;

/**
 * Class PluginInstallQualityCrawlerJiraIssuesIndex
 *
 * @package extas\components\plugins
 * @author jeyroik@gmail.com
 */
class PluginInstallQualityCrawlerJiraIssuesIndex extends PluginInstallDefault
{
    protected $selfUID = JiraIssuesIndex::FIELD__MONTH;
    protected $selfRepositoryClass = IJiraIssuesIndexRepository::class;
    protected $selfSection = 'quality_jira_issues_index';
    protected $selfName = 'quality jira issues-index';
    protected $selfItemClass = JiraIssuesIndex::class;
}
