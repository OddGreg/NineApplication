<?php namespace Nine\Application\Events;

/**
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Nine\Application\Application;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplicationTerminateEvent extends Event
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * ApplicationTerminateEvent constructor.
     *
     * @param Application $app
     * @param Request     $request
     * @param Response    $response
     */
    public function __construct($app, $request, $response)
    {
        $this->app = $app;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return Application
     */
    public function getApp(): Application
    {
        return $this->app;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}
