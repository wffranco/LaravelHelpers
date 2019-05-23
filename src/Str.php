<?php

namespace Wffranco\Helpers;

use Illuminate\Support\Str as Lstr;
use Illuminate\Support\Traits\Macroable;

class Str
{
    use Macroable;

    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $dotCache = [];

    public static function normalice($cadena)
    {
        $replacer = [
            'ÀÁÂÃÄÅÆÞÇÐÈÉÊËÌÍÎÏÑÒÓÔÕÖØŔÙÚÛÜÝàáâãäåæþçðèéêëìíîïñòóôõöøŕùúûýýÿαβßπ',
            'AAAAAAABCDEEEEIIIINOOOOOORUUUUYaaaaaaabcdeeeeiiiinooooooruuuyyyabbp',
        ];
        return strtr($cadena, $replacer[0], $replacer[1]);
    }

    public static function dot($title, $separator = '.') {
        $title = static::normalice($title);
        $title = preg_replace('/[^0-9a-za-Z]+/', $separator, $title);
        return strtolower(preg_replace('/([^0-9a-zA-Z])(?=[A-Z])/u', '$1'.$separator, $title));
    }

    public static function build_url(...$args) {
        $data = [];
        while (($next = array_shift($args)) || count($args)) {
            if (is_string($next)) $next = parse_url($next);
            if (!$next) continue;
            if(isset($next['query'])) {
                $query = $next['query'];
                unset($next['query']);
            }
            $data = array_merge($data, $next);
            if ($query) {
                isset($data['query']) ? $data['query'][] = $query : $data['query'] = [$query];
            }
            $query = null;
        }
        $data = (object)$data;
        $url = isset($data->scheme) ? $data->scheme . ':' : '';
        if (isset($data->host)) {
            $url .= '//';
            $user = $data->user;
            if (isset($data->pass)) $user .= ':' . $data->pass;
            if ($user) $url .= $user . '@';
            $url .= $data->host;
        }
        if (isset($data->port)) $url .= ':' . $data->port;
        if (isset($data->path)) $url .= $data->path;
        if (isset($data->query)) {
            parse_str(implode('&', $data->query), $query);
            $url .= '?' . http_build_query($query);
        }
        if (isset($data->fragment)) $url .= '#' . $data->fragment;

        return $url;
    }
}
