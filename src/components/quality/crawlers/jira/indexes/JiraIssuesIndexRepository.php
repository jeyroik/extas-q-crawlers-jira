<?php
namespace extas\components\quality\crawlers\jira\indexes;

use extas\components\repositories\Repository;
use extas\interfaces\quality\crawlers\jira\indexes\IJiraIssuesIndexRepository;

/**
 * Class JiraIssuesIndexRepository
 *
 * @package extas\components\quality\crawlers\jira\indexes
 * @author jeyroik@gmail.com
 */
class JiraIssuesIndexRepository extends Repository implements IJiraIssuesIndexRepository
{
    protected $itemClass = JiraIssuesIndex::class;
    protected $name = 'quality_jira_issues_indexes';
    protected $pk = JiraIssuesIndex::FIELD__MONTH;
    protected $scope = 'extas';
    protected $idAs = '';
}
