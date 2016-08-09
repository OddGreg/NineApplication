<?php namespace Nine\Application\Events;

/**
 * @package Nine Kernel
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class ApplicationRequestEvent extends Event
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}
