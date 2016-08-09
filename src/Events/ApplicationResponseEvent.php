<?php namespace Nine\Application\Events;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package Nine Application
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */
class ApplicationResponseEvent extends Event
{
    private $request;

    private $response;

    public function __construct(Response $response, Request $request)
    {
        $this->response = $response;
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
