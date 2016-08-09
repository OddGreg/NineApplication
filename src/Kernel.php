<?php namespace Nine\Application;

/**
 * @package Nine Application
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Kernel extends HttpKernel
{
    /** @var UrlMatcher $matcher */
    protected $matcher;

    /** @var RouteCollection $routeCollection */
    protected $routeCollection;

    /**
     * Kernel constructor.
     *
     * @param EventDispatcher             $dispatcher
     * @param RouteCollection             $routeCollection
     * @param ControllerResolverInterface $controllerResolver
     * @param ArgumentResolverInterface   $argumentResolver
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        EventDispatcher $dispatcher,
        RouteCollection $routeCollection,
        ControllerResolverInterface $controllerResolver,
        ArgumentResolverInterface $argumentResolver
    ) {

        $this->routeCollection = $routeCollection;
        $this->matcher = new UrlMatcher($routeCollection, New RequestContext());

        // create from parent first
        parent::__construct($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

        $this->addEvents();
    }

    public function addEvents()
    {
        //$this->dispatcher->addSubscriber(new RouterListener($this->matcher, $this->requestStack));
    }

}
