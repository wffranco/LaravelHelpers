<?php

namespace Wffranco\Helpers;

use Illuminate\Support\Str;

class AndOr {
    private const T = '`true´';
    private const F = '`false´';

    public static function validate ($values, callable $callback)
    {
        if (is_array($values)) return static::validateOr($values, $callback);
        if (!is_string($values)) return false;

        $values = static::evalBrackets($values, $callback);
        if (Str::contains($values, '|')) return static::validateOr($values, $callback);
        if (Str::contains($values, '&')) return static::validateAnd($values, $callback);
        return static::validateOne($values, $callback);
    }

    public static function validateOne(string $value, callable $callback)
    {
        return $value === static::T || $value !== static::F && $callback($value);
    }

    protected static function evalBrackets(string $value, callable &$callback)
    {
        if (!is_string($value)) return $value;

        while (strpos($value, '(') !== false && strpos($value, ')') !== false) {
            $value = preg_replace_callback('/\\(([^\\(\\)]*)\\)/', function ($matches) use ($callback) {
                return static::validate($matches[1], $callback) ? static::T : static::F;
            }, $value);
        }

        return $value;
    }

    protected static function validateAnd($values, callable &$callback)
    {
        if (is_string($values)) $values = explode('&', static::evalBrackets($values, $callback));

        if (!is_array($values) || !count($values)) return false;

        foreach($values as $value) {
            if (is_array($value) || (is_string($value) && Str::contains($value, '|'))) {
                if (!static::validateOr($value, $callback)) return false;
            } elseif (is_string($value)) {
                if (!static::validateOne($value, $callback)) return false;
            } else {
                return false;
            }
        }

        return true;
    }

    protected static function validateOr($values, callable &$callback)
    {
        if (is_string($values)) $values = explode('|', static::evalBrackets($values, $callback));

        if (!is_array($values) || !count($values)) return false;

        foreach($values as $value) {
            if (is_array($value) || (is_string($value) && Str::contains($value, '&'))) {
                if (static::validateAnd($value, $callback)) return true;
            } elseif (is_string($value)) {
                if (static::validateOne($value, $callback)) return true;
            } else {
                return false;
            }
        }

        return false;
    }
}
