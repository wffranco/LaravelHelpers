<?php

use Wffranco\Helpers\Str;

// Helper functions come here

if (!function_exists('db_env')) {
    // Convert a env string into a Laravel DB array.
    function db_env($name, $default = '') {
        return Str::db_uri(env($name, $default));
    }
}
