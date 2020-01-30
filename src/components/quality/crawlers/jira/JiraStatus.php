<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\components\THasDescription;
use extas\components\THasId;
use extas\components\THasName;
use extas\interfaces\quality\crawlers\jira\IJiraStatus;

/**
 * Class JiraStatus
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraStatus extends Item implements IJiraStatus
{
    use THasId;
    use THasName;
    use THasDescription;

    /**
     * @return string
     */
    public function getCategoryName(): string
    {
        $category = $this->config[static::FIELD__CATEGORY] ?? [];

        return $category[static::FIELD__NAME] ?? '';
    }

    /**
     * @return bool
     */
    public function isDone(): bool
    {
        return $this->getCategoryName() == static::CATEGORY__DONE;
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
