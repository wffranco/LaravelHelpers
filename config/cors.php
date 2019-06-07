<?php

return [
    'headers' => [
        'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, DELETE',
        'Access-Control-Allow-Headers' => 'Origin, Content-Type, X-Auth-Token',
        'Access-Control-Max-Age' => '5', // time in seconds
    ],

    'credentials' => true, // if enabled, provide origins
    'origins' => [
        'http://localhost', // localhost example
    ],
];
