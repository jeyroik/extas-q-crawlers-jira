<?php
namespace extas\components\quality\crawlers\jira;

use extas\interfaces\quality\crawlers\jira\IHasRate;

/**
 * Trait THasRate
 *
 * @property $config
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
trait THasRate
{
    /**
     * @return float
     */
    public function getRate(): float
    {
        return (float) ($this->config[IHasRate::FIELD__RATE] ?? 0);
    }

    /**
     * @param float $rate
     *
     * @return $this
     */
    public function setRate(float $rate)
    {
        $this->config[IHasRate::FIELD__RATE] = $rate;

        return $this;
    }
}
