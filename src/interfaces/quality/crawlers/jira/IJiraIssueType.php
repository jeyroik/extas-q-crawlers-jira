<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasId;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;

/**
 * Interface IJiraIssueType
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraIssueType extends IItem, IHasName, IHasId, IHasDescription
{
    public const SUBJECT = 'extas.quality.crawler.jira.issue.type';
}
