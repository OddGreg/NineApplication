<?php namespace Nine\Application\Configurations;

use Nine\Application\Configurations\Traits\WithImportDependencies;
use Nine\Application\Containers\AurynDI;
use Nine\Loaders\Configurator;

/**
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */
class CoreConfigurator extends Configurator
{
    use WithImportDependencies;

    /**
     * Entry Method
     *
     * @internal param array $parameters Optional configuration parameters.
     *
     * @param AurynDI $di
     */
    public function apply(AurynDI $di)
    {
        $this->importDependencies($di);
    }
}
