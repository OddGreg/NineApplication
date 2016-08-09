<?php

/**
 * Utility access functions.
 *
 * Note: the App class MUST have been instantiated before any
 *       of these functions will work.
 *
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Nine\Application\Containers\Contracts\ContainerCompatibilityInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

//------------------------------------------------------
// Application App functions.
//------------------------------------------------------

if ( ! function_exists('app')) {

    /**
     * @return \Nine\Application\Application
     */
    function app()
    {
        return App::app();
    }
}

if ( ! function_exists('config')) {

    /**
     * @param null $query
     *
     * @return \Nine\Loaders\ConfigFileReader
     */
    function config($query = NULL)
    {
        return App::config($query);
    }
}

if ( ! function_exists('path')) {

    /**
     * @param null $path
     *
     * @return mixed|\Nine\Collections\Paths
     */
    function path($path = NULL)
    {
        return App::path($path);
    }
}

if ( ! function_exists('di')) {

    /**
     * @param string $abstract
     *
     * @return ContainerCompatibilityInterface
     */
    function di(string $abstract = NULL)
    {
        return $abstract ? App::container()->get($abstract) : App::container();
    }
}

if ( ! function_exists('events')) {

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    function events()
    {
        return App::dispatcher();
    }
}

//------------------------------------------------------
// Routing helper functions.
//------------------------------------------------------

if ( ! function_exists('request')) {

    /**
     * @param string $path
     * @param string $method
     * @param array  $parameters
     * @param string $content
     *
     * @return Request
     */
    function request(string $path, $method = 'GET', array $parameters = [], $content = '')
    {
        return Request::create($path, $method, $parameters, [], [], [], $content);
    }
}

if ( ! function_exists('route')) {

    /**
     * A convenience for generating new routes.
     *
     * @param string $name
     * @param string $path
     *
     * @return Route|RouteCollection
     */
    function route(string $name = NULL, string $path = NULL)
    {
        if (NULL === $name) {
            return App::routes();
        }

        $route = new Route($path);
        App::routes()->add($name, $route);

        return $route;
    }
}

if ( ! function_exists('routes')) {

    /**
     * @return \Symfony\Component\Routing\RouteCollection
     */
    function routes()
    {
        return App::routes();
    }
}

if ( ! function_exists('response')) {

    /**
     * @param       $content
     * @param int   $status
     * @param array $headers
     *
     * @return Response
     */
    function response($content, $status = 200, array $headers = [])
    {
        return Response::create($content, $status, $headers);
    }
}
