<?php

namespace Wffranco\Helpers;

use Illuminate\Support\Str as SupportStr;

class Str extends SupportStr
{
    /**
     * The cache of snake-cased words.
     *
     * @var array
     */
    protected static $dotCache = [];

    public static function normalize($cadena)
    {
        $replacer = [
            'ÀÁÂÃÄÅÆÞÇÐÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåæþçðèéêëìíîïñòóôõöøùúûýýÿß',
            'AAAAAAABCDEEEEIIIINOOOOOOUUUUYaaaaaaabcdeeeeiiiinoooooouuuyyyb',
        ];
        return strtr(utf8_decode($cadena), utf8_decode($replacer[0]), $replacer[1]);
    }

    public static function dot($title, $separator = null) {
        $separator or $separator = '.';
        $title = static::normalize($title);
        $title = preg_replace('/[^0-9a-zA-Z]+/', $separator, $title);
        return trim(strtolower(preg_replace('/([0-9a-z])(?=[A-Z])/u', '$1'.$separator, $title)), $separator);
    }

    public static function camel($title) {
        $title = static::normalize($title);
        $title = trim(preg_replace('/[^0-9a-zA-Z]+/', ' ', $title));
        return lcfirst(preg_replace_callback('/\s(.)/u', function($match) {
            return strtoupper($match[1]);
        }, $title));
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
