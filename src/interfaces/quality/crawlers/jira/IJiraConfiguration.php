<?php
namespace extas\interfaces\quality\crawlers\jira;

use extas\interfaces\IItem;

/**
 * Interface IJiraConfiguration
 *
 * @package extas\interfaces\quality\crawlers\jira
 * @author jeyroik@gmail.com
 */
interface IJiraConfiguration extends IItem
{
    const SUBJECT = 'extas.quality.crawlers.jirs.configuration';

    const FIELD__IS_WRAPPED = 'wrapper_is';
    const FIELD__WRAPPER_TOKEN = 'wrapper_token';
    const FIELD__LOGIN = 'login';
    const FIELD__PASSWORD = 'password';
    const FIELD__BUG_TYPES = 'bugs';
    const FIELD__ENDPOINT = 'endpoint';
    const FIELD__ENDPOINT_VERSION = 'endpoint_version';

    /**
     * @return IJiraConfiguration
     */
    public static function load(): IJiraConfiguration;

    /**
     * @return bool
     */
    public function getIsWrapped(): bool;

    /**
     * @return string
     */
    public function getWrapperToken(): string;

    /**
     * @return string
     */
    public function getLogin(): string;

    /**
     * @return string
     */
    public function getPassword(): string;

    /**
     * @return array [bugType => true]
     */
    public function getBugTypes(): array;

    /**
     * @return string
     */
    public function getEndpoint(): string;

    /**
     * @return string
     */
    public function getEndpointVersion(): string;
}
