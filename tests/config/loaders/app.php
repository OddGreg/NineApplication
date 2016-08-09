<?php
/**
 * @package Nine
 * @version 0.4.2
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Nine\Application\Application;
use Nine\Application\Containers\AurynDI;
use Nine\Application\Containers\Contracts\ContainerCompatibilityInterface;
use Nine\Application\Events\Events;
use Nine\Collections\Config;
use Nine\Collections\GlobalScope;
use Nine\Collections\Paths;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

return [
    // building the application
    'alias'    => [
        //EventDispatcherInterface::class        => Events::class,
        ContainerCompatibilityInterface::class => AurynDI::class,
        'paths'                                => Paths::class,
    ],
    'extend'   => [
        //IlluminateContainer::class =>
        //    function ($container, $injector) {
        //        /** @var IlluminateContainer $container */
        //        /** @var Injector $injector */
        //        $container::setInstance($injector->make(Forge::class));
        //    },
    ],
    'define'   => [
        Config::class => [':items' => Config::createFromFolder(\CONFIG)],
        Paths::class  => [':data' => include BOOT . 'paths.php'],
    ],
    'delegate' => [
        //Events::class => Events::class . '::getInstance',
    ],
    'share'    => [
        Application::class,
        Events::class,
        Config::class,
        GlobalScope::class,
        Paths::class,
    ],
];
