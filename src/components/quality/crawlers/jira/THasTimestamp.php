<?php
namespace extas\components\quality\crawlers\jira;

use extas\interfaces\quality\crawlers\jira\IHasTimestamp;

/**
 * Trait THasTimestamp
 *
 * @property $config
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
trait THasTimestamp
{
    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->config[IHasTimestamp::FIELD__TIMESTAMP] ?? 0;
    }

    /**
     * @param int $timestamp
     *
     * @return $this
     */
    public function setTimestamp(int $timestamp)
    {
        $this->config[IHasTimestamp::FIELD__TIMESTAMP] = $timestamp;

        return $this;
    }
}
