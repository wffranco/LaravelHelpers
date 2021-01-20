<?php

namespace Wffranco\Helpers;

class Arr {
    /** Find element in array that match with the condition. */
    public static function find(?array $array, callable $callable) {
        foreach ($array ?? [] as $element) {
            if ($callable($element)) return $element;
        }
    }

    /** Count element in array that match with the condition. */
    public static function match_count(?array $array, callable $callable) {
        $count = 0;
        foreach ($array ?? [] as $element) {
            if ($callable($element)) $count++;
        }
        return $count;
    }

    /** Returns the first key from array where the element match the condition. */
    public static function first_key(?array $array, callable $callable) {
        foreach ($array ?? [] as $key => $element) {
            if ($callable($element)) return $key;
        }
    }

    /**
     * Returns only the specified keys from the given array.
     * If a key doesn't exist, added with null value.
     */
    public static function only(?array $array, ?array $keys) {
        if (!$array || !$keys) return;

        return static::map_assoc($keys, function($key) use($array) { return [$key => isset($array[$key]) ? $array[$key] : null]; });
    }

    /** Map assoc array. Preserve or set a new key returning an array pair [$key => $value]. */
    public static function map_assoc(?array $array, $callback) {
        $map = [];
        foreach ($array ?? [] as $key => $value) {
            $response = $callback($value, $key);
            if (is_array($response) && count($response) === 1 && (array_key_exists($key, $response) || !isset($response[0]))) {
                $map += $response;
            } else {
                $map[] = $response;
            }
        }

        return $map;
    }

    /**
     * Rename keys in array.
     * @param The second array must be pairs of [$old_key => $new_key].
     */
    public static function rename_keys(?array $array, ?array $keys, bool $overwrite = false) {
        if (!$array || !$keys) return $array;

        foreach ($keys as $old_key => $new_key) {
            if (!array_key_exists($new_key, $array) || $overwrite) {
                $array[$new_key] = $array[$old_key];
                unset($array[$old_key]);
            }
        }

        return $array;
    }
}
