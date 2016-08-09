<?php

/**
 * @package Nine Application
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/** @var RouteCollection $route */
$route->add('root', (new Route('/'))->setDefaults(['_controller' => function () { return response('Hello World!'); }]));
