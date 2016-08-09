<?php namespace Nine\Application\Events\Listeners;

use Nine\Application\Events\ApplicationResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */
class ApplicationResponseListener implements EventSubscriberInterface
{
    public function onResponse(ApplicationResponseEvent $event)
    {
        $response = $event->getResponse();

        if ($response->isRedirection()
            || 'html' !== $event->getRequest()->getRequestFormat()
            || ($response->headers->has('Content-Type')
                && FALSE === strpos($response->headers->get('Content-Type'), 'html'))
        ) {
            return;
        }

        $response->setContent($response->getContent());

        echo '(' . (string)$response . ')' . PHP_EOL;
    }

    public static function getSubscribedEvents()
    {
        return [
            'app.response' => 'onResponse',
        ];
    }

}
