<?php

namespace Wffranco\Roles;

use Illuminate\Support\Str as Lstr;

class AndOr {
    private const T = '`true´';
    private const F = '`false´';

    public static function validate ($values, callable $callback, bool $endsWithOr = true)
    {
        if (is_string($values)) {
            if (Lstr::contains($values, '(') && Lstr::contains($values, ')')) {
                $values = static::validateBrackets($values, $callback, $endsWithOr);
            }

            if (!Lstr::contains($values, ['&', '|'])) {
                return static::validateOne($values, $callback);
            }
        }

        return static::{'validate' . ($endsWithOr ? 'Or' : 'And')}($values, $callback);
    }

    public static function validateOne(string $value, callable $callback)
    {
        return $value === static::T ? true : ($value === static::F ? false : $callback(Str::dot($value)));
    }

    protected static function validateBrackets(string $value, callable &$callback, bool $endsWithOr = true)
    {
        while (Lstr::contains($value, '(') && Lstr::contains($value, ')')) {
            $value = preg_replace_callback('#\\(([^\\)]*)\\)#', function ($matches) use ($callback, $endsWithOr) {
                return static::validate($matches[1], $callback, $endsWithOr) ? static::T : static::F;
            }, $value);
        }

        return $value;
    }

    protected static function validateAnd($values, callable &$callback)
    {
        is_array($values) OR $values = is_string($values) ? explode('&', $values) : [];

        if (empty($values)) return false;

        foreach($values as $value) {
            if (is_array($value)) {
                $and = static::validateOr($value, $callback);
            } elseif (is_string($value)) {
                $and = static::{'validate' . (Lstr::contains($value, '|') ? 'Or' : 'One')}($value, $callback);
            }
            if (!$and) return false;
        }

        return true;
    }

    protected static function validateOr($values, callable &$callback)
    {
        is_array($values) OR $values = is_string($values) ? explode('|', $values) : [];

        if (empty($values)) return false;

        foreach($values as $value) {
            if (is_array($value)) {
                $or = static::validateAnd($value, $callback);
            } elseif (is_string($value)) {
                $or = static::{'validate' . (Lstr::contains($value, '&') ? 'And' : 'One')}($value, $callback);
            }
            if ($or) return true;
        }

        return false;
    }
}
