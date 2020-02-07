<?php
namespace extas\interfaces\quality\crawlers\jira;

/**
 * Interface IHasTimestamp
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IHasTimestamp
{
    const FIELD__TIMESTAMP = 'timestamp';

    /**
     * @return int
     */
    public function getTimestamp(): int;

    /**
     * @param int $timestamp
     *
     * @return $this
     */
    public function setTimestamp(int $timestamp);
}
