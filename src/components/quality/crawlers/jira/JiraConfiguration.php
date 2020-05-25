<?php
namespace extas\components\quality\crawlers\jira;

use extas\components\Item;
use extas\interfaces\quality\crawlers\jira\IJiraConfiguration;

/**
 * Class JiraConfiguration
 *
 * @package extas\components\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
class JiraConfiguration extends Item implements IJiraConfiguration
{
    /**
     * @var IJiraConfiguration
     */
    protected static ?IJiraConfiguration $instance = null;

    /**
     * @return IJiraConfiguration
     * @throws \Exception
     */
    public static function load(): IJiraConfiguration
    {
        return static::$instance ?: static::$instance = new static();
    }

    /**
     * JiraConfiguration constructor.
     *
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config = [])
    {
        $configPath = getenv('EXTAS__Q_JIRA_CONFIG_PATH') ?: '';

        if (!is_file($configPath)) {
            throw new \Exception(
                'Jira config path is undefined. Please, define EXTAS__Q_JIRA_CONFIG_PATH env param'
            );
        }

        $config = include $configPath;

        foreach ($this->getPluginsByStage('extas.quality.crawlers.jira.config') as $plugin) {
            $plugin($config);
        }

        parent::__construct($config);
    }

    public function getBVFieldId(): int
    {
        return (int) ($this->config[static::FIELD__BV_FIELD_ID] ?? 0);
    }

    /**
     * @return int
     */
    public function getReturnFieldId(): int
    {
        return (int) ($this->config[static::FIELD__RETURNS_FIELD_ID] ?? 0);
    }

    /**
     * @return array
     */
    public function getBugTypes(): array
    {
        return $this->config[static::FIELD__BUG_TYPES] ?? [];
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->config[static::FIELD__ENDPOINT] ?? '';
    }

    /**
     * @return string
     */
    public function getEndpointVersion(): string
    {
        return $this->config[static::FIELD__ENDPOINT_VERSION] ?? '';
    }

    /**
     * @return bool
     */
    public function getIsWrapped(): bool
    {
        return $this->config[static::FIELD__IS_WRAPPED] ?? false;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->config[static::FIELD__LOGIN] ?? '';
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->config[static::FIELD__PASSWORD] ?? '';
    }

    /**
     * @return string
     */
    public function getWrapperToken(): string
    {
        return $this->config[static::FIELD__WRAPPER_TOKEN] ?? '';
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
