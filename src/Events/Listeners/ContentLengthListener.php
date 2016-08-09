<?php namespace Nine\Application\Events\Listeners;

/**
 * @package Nine Application
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Nine\Application\Events\ApplicationResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ContentLengthListener implements EventSubscriberInterface
{
    public function onResponse(ApplicationResponseEvent $event)
    {
        $response = $event->getResponse();
        $headers = $response->headers;

        if ( ! $headers->has('Content-Length') && ! $headers->has('Transfer-Encoding')) {
            $headers->set('Content-Length', strlen($response->getContent()));
        }
    }

    public static function getSubscribedEvents()
    {
        return ['response' => ['onResponse', -255]];
    }
}
