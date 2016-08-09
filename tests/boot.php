<?php

/**
 * @package Nine Application
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Nine\Application\AppBuilder;
use Nine\Application\Containers\AurynDI;
use Nine\Collections\Paths;

// debug and testing helpers
$paths = include __DIR__ . '/support/paths.php';
include __DIR__ . '/support/debug.php';
include __DIR__ . '/support/helpers.php';

include $paths['vendor'] . 'autoload.php';

$app = AppBuilder::make(new AurynDI, new Paths($paths));
//app()->run();
