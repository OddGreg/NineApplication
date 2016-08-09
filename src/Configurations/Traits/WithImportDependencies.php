<?php namespace Nine\Application\Configurations\Traits;

/**
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Nine\Application\Containers\AurynDI;

/**
 * Provides a protected function to Configurators for importing dependencies
 * via a formatted configuration (accessible through the getSettings() method.)
 */
trait WithImportDependencies
{
    /**
     * @param AurynDI $di
     */
    protected function importDependencies(AurynDI $di)
    {
        // retrieve the specific configuration settings for a
        // the Configurator that uses this trait.
        $config = $this->getSettings();

        // ------------------------------------------------------------------
        //  Aliases - Type-Hint Aliasing
        // ------------------------------------------------------------------
        if (isset($config['aliases'])) {
            foreach ((array)$config['aliases'] as $original => $alias) {
                $di->alias($original, $alias);
            }
        }

        // ------------------------------------------------------------------
        //  Defines - Define a class with concrete parameter values.
        //            ie: define('MyClass', [:parameter_1 => 'something']...)
        // ------------------------------------------------------------------
        if (isset($config['defines'])) {
            foreach ((array)$config['defines'] as $class => $args) {
                $di->define($class, $args);
            }
        }

        // ------------------------------------------------------------------
        //  Delegates - Delegates instantiation to a callable or method.
        // ------------------------------------------------------------------
        if (isset($config['delegates'])) {
            foreach ((array)$config['delegates'] as $class => $callableOrMethodStr) {
                $di->delegate($class, $callableOrMethodStr);
            }
        }

        // ------------------------------------------------------------------
        //  Parameters - Add un-type-hinted parameters by name.
        //               Useful for cases such as:
        //                  Class::Method($untyped_parameter) where
        //                  $untyped_parameter is a specific use parameter.
        // ------------------------------------------------------------------
        if (isset($config['parameters'])) {
            foreach ((array)$config['parameters'] as $parameter => $value) {
                $di->defineParam($parameter, $value);
            }
        }

        // ------------------------------------------------------------------
        //  Prepares - Provide additional instantiation options|instructions
        // ------------------------------------------------------------------
        if (isset($config['prepares'])) {
            foreach ((array)$config['prepares'] as $class => $callableOrMethodStr) {
                $di->prepare($class, $callableOrMethodStr);
            }
        }

        // ------------------------------------------------------------------
        //  Shares
        // ------------------------------------------------------------------
        if (isset($config['shares'])) {
            foreach ((array)$config['shares'] as $share) {
                $di->share($share);
            }
        }
    }

}
