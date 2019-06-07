<?php

namespace Wffranco\Helpers\Http\Middleware;

use Closure;
use Response;

class Cors {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request)->withHeaders(config('cors.headers', []));
        $credentials = config('cors.credentials', false);
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $origins = config('cors.origins', []);

        if (!$credentials || !count($origins)) $response->header('Access-Control-Allow-Origin', '*');
        elseif (in_array($origin, $origins)) $response->header('Access-Control-Allow-Origin', $origin);

        if ($credentials) $response->header('Access-Control-Allow-Credentials', 'true');

        return $response;
    }

}
