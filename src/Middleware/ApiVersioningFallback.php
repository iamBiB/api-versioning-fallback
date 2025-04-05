<?php

/**
 * @Author: BiB
 * @Date:   2023-02-15 08:08:27
 * @Last Modified by:   Bogdan Bocioaca
 * @Last Modified time: 2023-03-13 19:55:59
 */
/* #######################################  */
/*      I don`t always test my code         */
/*  But when I do, I do it in production    */
/* #######################################  */

namespace iAmBiB\ApiVersionFallback\Middleware;

use Closure;
use FastRoute\Dispatcher;
use Illuminate\Http\Request;

class ApiVersioningFallback
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!config('api-versioning.enable'))
        {
            return $next($request);
        }
        [$method, $pathInfo] = [$request->getMethod(), '/' . trim($request->getPathInfo(), '/')];

        if (isset(app()->router->getRoutes()[$method . $pathInfo]))
        {
            return $next($request);
        }
        $api_fallbacks = config('api-versioning.api_fallbacks');
        $available_version = config('api-versioning.available_versions');
        $segments = $request->segments();
        $segment_no = data_get(config('api-versioning'), 'segment_no', 0);
        if (!isset($segments[$segment_no]) || !\in_array($segments[$segment_no], $available_version))
        {
            return $next($request);
        }
        foreach ($api_fallbacks as $fallback)
        {
            $segments[0] = $fallback;
            $path = '/' . implode('/', $segments);
            $result = $this->handleDispatcherResponse(
                app()->createDispatcher()->dispatch($method, $path)
            );
            if ($result)
            {
                break;
            }
        }
        if (!$result)
        {
            return $next($request);
        }

        return app()->handleFoundRoute($result);
    }

    protected function handleDispatcherResponse($routeInfo)
    {
        switch ($routeInfo[0])
        {
            case Dispatcher::NOT_FOUND:
            case Dispatcher::METHOD_NOT_ALLOWED:
                return false;
            case Dispatcher::FOUND:
                return $routeInfo;
        }
    }
}
