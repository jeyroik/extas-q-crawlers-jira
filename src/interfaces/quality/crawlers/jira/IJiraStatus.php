<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IHasDescription;
use extas\interfaces\IHasId;
use extas\interfaces\IHasName;
use extas\interfaces\IItem;

/**
 * Interface IJiraStatus
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraStatus extends IItem, IHasName, IHasId, IHasDescription
{
    public const SUBJECT = 'extas.quality.crawler.jira.status';

    public const FIELD__CATEGORY = 'statusCategory';

    public const CATEGORY__DONE = 'Done';
    public const CATEGORY__IN_PROGRESS = 'In progress';
    public const CATEGORY__TODO = 'To do';
    public const CATEGORY__REVIEW = 'Code Review';

    /**
     * @return string
     */
    public function getCategoryName(): string;

    /**
     * @return bool
     */
    public function isDone(): bool;
}
