<?php
namespace extas\components\quality\crawlers\jira;

use extas\interfaces\quality\crawlers\jira\IHasMonth;

/**
 * Trait THasMonth
 *
 * @property $config
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
trait THasMonth
{
    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->config[IHasMonth::FIELD__MONTH] ?? 0;
    }

    /**
     * @param int $month
     *
     * @return $this
     */
    public function setMonth(int $month)
    {
        $this->config[IHasMonth::FIELD__MONTH] = $month;

        return $this;
    }
}
