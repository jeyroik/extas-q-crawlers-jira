<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasId;
use extas\components\THasName;
use extas\interfaces\quality\crawlers\jira\IJiraIssueType;

/**
 * Class JiraIssueType
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraIssueType extends Item implements IJiraIssueType
{
    use THasName;
    use THasDescription;
    use THasId;

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
