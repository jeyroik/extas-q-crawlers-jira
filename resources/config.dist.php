<?php

use extas\interfaces\quality\crawlers\jira\IJiraConfiguration as I;

return [
    I::FIELD__IS_WRAPPED => false,
    I::FIELD__WRAPPER_TOKEN => '',
    I::FIELD__LOGIN => '',
    I::FIELD__PASSWORD => '',
    I::FIELD__BUG_TYPES => [
        'Bug' => true
    ],
    I::FIELD__ENDPOINT => '',
    I::FIELD__ENDPOINT_VERSION => '/rest/api/latest/'
];