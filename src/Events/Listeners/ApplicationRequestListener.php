<?php namespace Nine\Application\Events\Listeners;

use Nine\Application\Events\ApplicationRequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */
class ApplicationRequestListener implements EventSubscriberInterface
{
    public function onRequest(ApplicationRequestEvent $event)
    {
        echo 'Request:' . (string)$event->getRequest() . PHP_EOL;
    }

    public static function getSubscribedEvents()
    {
        return [
            'app.request' => [['onRequest', 32]],
        ];
    }
}
