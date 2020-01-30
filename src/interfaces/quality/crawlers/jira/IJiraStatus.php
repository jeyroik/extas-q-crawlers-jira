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
    const SUBJECT = 'extas.quality.crawler.jira.status';

    const FIELD__CATEGORY = 'statusCategory';

    const CATEGORY__DONE = 'Done';
    const CATEGORY__IN_PROGRESS = 'In progress';
    const CATEGORY__TODO = 'To do';
    const CATEGORY__REVIEW = 'Code Review';

    /**
     * @return string
     */
    public function getCategoryName(): string;

    /**
     * @return bool
     */
    public function isDone(): bool;
}
