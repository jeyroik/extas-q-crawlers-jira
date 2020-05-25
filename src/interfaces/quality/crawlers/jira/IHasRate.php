<?php
namespace extas\interfaces\quality\crawlers\jira;

/**
 * Interface IHasRate
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IHasRate
{
    public const FIELD__RATE = 'rate';

    /**
     * @return float
     */
    public function getRate(): float;

    /**
     * @param float $rate
     *
     * @return $this
     */
    public function setRate(float $rate);
}
