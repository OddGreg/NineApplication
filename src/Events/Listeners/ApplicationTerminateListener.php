<?php namespace Nine\Application\Events\Listeners;

use Nine\Application\Events\ApplicationTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

class ApplicationTerminateListener implements EventSubscriberInterface
{

    /**
     * @param ApplicationTerminateEvent $event
     */
    public function onTerminate(ApplicationTerminateEvent $event)
    {
        echo PHP_EOL . 'application terminated' . PHP_EOL;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            'app.terminate' => 'onTerminate',
        ];

    }
}
