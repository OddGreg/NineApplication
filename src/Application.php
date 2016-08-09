<?php namespace Nine\Application;

/**
 * @package Nine Application
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use App;
use Interop\Container\ContainerInterface;
use Nine\Application\Containers\Contracts\ContainerCompatibilityInterface;
use Nine\Application\Events\ApplicationRequestEvent;
use Nine\Application\Events\ApplicationResponseEvent;
use Nine\Application\Events\ApplicationTerminateEvent;
use Nine\Application\Events\Listeners\ApplicationRequestListener;
use Nine\Application\Events\Listeners\ApplicationResponseListener;
use Nine\Application\Events\Listeners\ApplicationTerminateListener;
use Nine\Application\Events\Listeners\ContentLengthListener;
use Nine\Application\Events\Listeners\TokenListener;
use Nine\Application\Handlers\ApplicationExceptionHandler;
use Nine\Collections\Paths;
use Nine\Loaders\ConfigFileReader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ExceptionListener;
use Symfony\Component\Routing\RouteCollection;

class Application extends Kernel
{
    const VERSION = '0.5.0';

    /** @var ConfigFileReader $config */
    protected $config;

    /** @var  ContainerCompatibilityInterface $container */
    protected $container;

    /** @var ControllerResolver $controllerResolver */
    protected $controllerResolver;

    /** @var Kernel $kernel */
    protected $kernel;

    /** @var Paths $paths */
    protected $paths;

    /** @var RouteCollection $routes */
    protected $routes;

    /**
     * Application constructor.
     *
     * @param ConfigFileReader $config
     * @param EventDispatcher  $dispatcher
     * @param RouteCollection  $routes
     * @param Paths            $paths
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(
        ConfigFileReader $config,
        EventDispatcher $dispatcher,
        RouteCollection $routes,
        Paths $paths
    ) {
        #@formatter:off
        $this->config             = $config;
        $this->controllerResolver = new ControllerResolver();
        $this->paths              = $paths;
        //$this->routes             = $routes;
        #@formatter:on

        $this->kernel = parent::__construct($dispatcher, $routes, $this->controllerResolver, new ArgumentResolver);

        // subscribe to core events
        $this->addSubscribers();

        // clear and redefine the App class - in the event that it
        // was instantiated by another application.
        App::reset();
        App::createFromApplication($this);
    }

    /**
     * Register Application events and listeners.
     */
    public function addSubscribers()
    {
        $this->dispatcher->addSubscriber(new ExceptionListener((new ApplicationExceptionHandler())->getHandler()));
        $this->dispatcher->addSubscriber(new ContentLengthListener);
        $this->dispatcher->addSubscriber(new ApplicationResponseListener);
        $this->dispatcher->addSubscriber(new ApplicationRequestListener);
        $this->dispatcher->addSubscriber(new ApplicationTerminateListener);
        $this->dispatcher->addSubscriber(new TokenListener(['access' => '1234567890']));
    }

    /**
     * @return ContainerCompatibilityInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        // clear and redefine the App class - in the event that it
        // was instantiated by another application.
        App::reset();
        App::createFromApplication($this);
    }

    /**
     * This method exposes the internal or global objects which
     * lie at the core of the application. It is primarily used
     * by the \App helper singleton and helper functions.
     *
     * @return array
     */
    public function getGlobals() : array
    {
        return [
            'app'        => $this,
            'config'     => $this->config,
            'container'  => $this->container,
            'dispatcher' => $this->dispatcher,
            'paths'      => $this->paths,
            'routes'     => $this->routeCollection,
            //'routes'     => $this->routes,
            'matcher'    => $this->matcher,
        ];
    }

    /**
     * @param Request|null $request
     *
     * @throws \Exception
     */
    public function run(Request $request = NULL)
    {
        // run() allows passing a custom request
        $request = $request ?? Request::createFromGlobals();

        $this->dispatcher->dispatch('app.request', new ApplicationRequestEvent($request));
        /** @var Response $response */
        $response = $this->handle($request);
        $this->dispatcher->dispatch('app.response', new ApplicationResponseEvent($response, $request));

        // if we get here then maybe it's ok.
        $response->send();

        // ta-da
        $this->dispatcher->dispatch('app.terminate', new ApplicationTerminateEvent($this, $request, $response));
        $this->terminate($request, $response);
    }

}
