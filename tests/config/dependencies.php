<?php

/**
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Nine\Application\Application;
use Nine\Application\Containers\Contracts\ContainerCompatibilityInterface;
use Nine\Application\Controllers\ExceptionController;
use Symfony\Component\EventDispatcher\EventDispatcher;

return [
    'aliases'  => [
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
    'defines'  => [
        Symfony\Component\HttpKernel\EventListener\ResponseListener::class  => [
            ':charset' => 'UTF-8',
        ],
        Symfony\Component\HttpKernel\EventListener\ExceptionListener::class => [
            ':controller' => Nine\Application\Controllers\ExceptionController::class . '::exceptionAction',
            ':logger'     => NULL,
        ],
    ],
    'prepares' => [
        EventDispatcher::class => function (EventDispatcher $events, ContainerCompatibilityInterface $container) {
            $events->addSubscriber($container->get('listener.exception'));
            $events->addSubscriber($container->get('listener.response'));
            $events->addSubscriber($container->get('listener.router'));
        },
        Application::class     => function (Application $application, $container) {
            $application->setContainer($container);
        },
    ],
];
