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
    public const SUBJECT = 'extas.quality.crawlers.jirs.configuration';

    public const FIELD__IS_WRAPPED = 'wrapper_is';
    public const FIELD__WRAPPER_TOKEN = 'wrapper_token';
    public const FIELD__LOGIN = 'login';
    public const FIELD__PASSWORD = 'password';
    public const FIELD__BUG_TYPES = 'bugs';
    public const FIELD__ENDPOINT = 'endpoint';
    public const FIELD__ENDPOINT_VERSION = 'endpoint_version';
    public const FIELD__BV_FIELD_ID = 'bv_id';
    public const FIELD__RETURNS_FIELD_ID = 'return_id';

    /**
     * @return IJiraConfiguration
     */
    public static function load(): IJiraConfiguration;

    /**
     * @return int
     */
    public function getReturnFieldId(): int;

    /**
     * @return int
     */
    public function getBVFieldId(): int;

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
