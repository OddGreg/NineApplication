<?php

/**
 * @package Nine
 * @version 0.4.2
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Nine\Application\Application;
use Nine\Application\Containers\AurynDI;
use Nine\Application\Controllers\Controller;
use Nine\Library\Support;
use Nine\Loaders\ConfigFileReader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RouteCollection;

// Use the BaseController to determine the controller namespace.
// This assumes that the BaseController namespace is the same as
// any other controller.
$base = Support::parse_class_name(Controller::class);
$controller_namespace = implode("\\", $base['namespace']) . "\\";

return [
    //
    // -----------------------------------------------------------------
    // = Routing and Dispatch settings
    // -----------------------------------------------------------------
    //
    'controller_namespace' => $controller_namespace,

    //
    // -----------------------------------------------------------------
    // = Determine whether the Application uses/creates configuration
    // = caches.
    // -----------------------------------------------------------------
    //
    'use_cache'            => FALSE,
    'cache_path'           => CACHE,

    //
    // -----------------------------------------------------------------
    // = if the HttpCacheServiceProvider is registered, then use this
    // = as the default cache location.
    // -----------------------------------------------------------------
    //
    'http_cache_dir'       => CACHE . 'http/',

    // ------------------------------------------------------------------
    //  Aliases - Type-Hint Aliasing
    // ------------------------------------------------------------------
    'aliases'              => [
        'application'         => Nine\Application\Application::class,
        'argument_resolver'   => Symfony\Component\HttpKernel\Controller\ArgumentResolver::class,
        'context'             => Symfony\Component\Routing\RequestContext::class,
        'controller_resolver' => Symfony\Component\HttpKernel\Controller\ControllerResolver::class,
        'dispatcher'          => Symfony\Component\EventDispatcher\EventDispatcher::class,
        'events'              => Symfony\Component\EventDispatcher\EventDispatcher::class,
        'listener.exception'  => Symfony\Component\HttpKernel\EventListener\ExceptionListener::class,
        'listener.response'   => Symfony\Component\HttpKernel\EventListener\ResponseListener::class,
        'listener.router'     => Symfony\Component\HttpKernel\EventListener\RouterListener::class,
        'matcher'             => Symfony\Component\Routing\Matcher\UrlMatcher::class,
        'request_stack'       => Symfony\Component\HttpFoundation\RequestStack::class,
        'route.collection'    => Symfony\Component\Routing\RouteCollection::class,
    ],

    // ------------------------------------------------------------------
    //  Defines - Define a class with concrete parameter values.
    //            ie: define('MyClass', [:parameter_1 => 'something']...)
    // ------------------------------------------------------------------
    'defines'              => [
        Symfony\Component\HttpKernel\EventListener\ResponseListener::class  => [
            ':charset' => 'UTF-8',
        ],
        Symfony\Component\HttpKernel\EventListener\ExceptionListener::class => [
            ':controller' => NULL, # Nine\Application\Controllers\ExceptionController::class . '::exceptionAction',
            ':logger'     => NULL,
        ],
    ],

    // ------------------------------------------------------------------
    //  Delegates - Delegates instantiation to a callable or method.
    // ------------------------------------------------------------------
    'delegates'            => [],

    // ------------------------------------------------------------------
    //  Parameters - Add un-type-hinted parameters by name.
    //               Useful for cases such as:
    //                  Class::Method($untyped_parameter) where
    //                  $untyped_parameter is a specific use parameter.
    // ------------------------------------------------------------------
    'parameters'           => [
    ],

    // ------------------------------------------------------------------
    //  Prepares - Provide additional instantiation options|instructions
    // ------------------------------------------------------------------
    'prepares'             => [
        EventDispatcher::class => function (EventDispatcher $events, AurynDI $di) {
            // listener.router needs $matcher
            $matcher = $di->get('matcher');
            $di->defineParam('matcher', $matcher);

            $events->addSubscriber($di->get('listener.exception'));
            $events->addSubscriber($di->get('listener.response'));
            $events->addSubscriber($di->get('listener.router'));
        },
        RouteCollection::class => function (RouteCollection $route, AurynDI $di) {
            $config = new ConfigFileReader(CONFIG);
            include $config['app.routes'];
        },
        Application::class     => function (Application $application, AurynDI $container) {
            $application->setContainer($container);
        },
    ],

    // ------------------------------------------------------------------
    //  Shares
    // ------------------------------------------------------------------
    'shares'               => [
    ],
];
