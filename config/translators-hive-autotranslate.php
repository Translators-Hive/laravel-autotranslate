<?php

return [

    /**
     * Localize types of translation strings.
     */
    'localize'  => [
        /**
         * Short keys. This is the default for Laravel.
         * They are stored in PHP files inside folders name by their locale code.
         * Laravel comes with default: auth.php, pagination.php, passwords.php and validation.php
         */
        'default'   => true,

        /**
         * Translations strings as key.
         * They are stored in JSON file for each locale.
         */
        'json'  => true,
    ],

    /**
     * Search criteria for files.
     */
    'search'    => [
        /**
         * Directories which should be looked inside.
         */
        'dirs'  => ['resources','app'],

        /**
         * Patterns by which files should be queried.
         * The values can be a regular expression, glob, or just a string.
         */
        'patterns'  => ['*.php'],

        /**
         * Functions that the strings will be collected from.
         * Add here any custom defined functions.
         * NOTE: The translation string should always be the first argument.
         */
        'functions' => [
            '__',
            'trans',
            '@lang'
        ]
    ],

    /**
     * Translators Hive Auth
     */
    'translators-hive-auth' => [
        /**
         * Password for translators-hive.com
         */
        'email' => env('TranslatorsHiveEmail'),

        /**
         * Password for translators-hive.com
         */
        'password'  => env('TranslatorsHivePassword'),

    ],

    /**
     * Sort extracted translation string keys alphabetically?
     */
    'sort'     => true,

];
