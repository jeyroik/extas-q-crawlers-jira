<?php
namespace extas\interfaces\quality\crawlers\jira;

/**
 * Interface IHasMonth
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IHasMonth
{
    public const FIELD__MONTH = 'month';

    /**
     * @return int
     */
    public function getMonth(): int;

    /**
     * @param int $month
     *
     * @return $this
     */
    public function setMonth(int $month);
}
