<?php namespace Nine\Application\Handlers;

/**
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response;

class ApplicationExceptionHandler implements ApplicationHandlerInterface
{
    public function getHandler()
    {
        return function (FlattenException $exception) {
            $msg = '[Nine] Something went wrong! (' . $exception->getMessage() . ')';

            return new Response($msg, $exception->getStatusCode());
        };
    }

}
