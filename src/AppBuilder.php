<?php namespace Nine\Application;

use Auryn\Injector;
use Interop\Container\ContainerInterface;
use Nine\Application\Containers\AurynDI;
use Nine\Application\Containers\Contracts\ContainerCompatibilityInterface;
use Nine\Application\Containers\Exceptions\ContainerRequirementMismatchException;
use Nine\Collections\Paths;
use Nine\Loaders\ConfigFileReader;
use Nine\Loaders\LoaderSet;
use Nine\Loaders\Support\LoaderReflector;
use Nine\Loaders\Support\SymbolTable;

/**
 * @package Nine Loader
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */
final class AppBuilder
{
    /** @var ConfigFileReader $config */
    protected static $config;

    /** @var AurynDI $container */
    protected static $container;

    /** @var LoaderSet $loader */
    protected static $loader;

    /** @var array $paths */
    protected static $paths;

    /**
     * @param AurynDI|ContainerCompatibilityInterface $container
     * @param array|Paths                             $paths
     *
     * @return Application
     */
    public static function make(ContainerCompatibilityInterface $container, Paths $paths) : Application
    {
        static::$config = new ConfigFileReader($paths['config']);
        static::$container = $container;
        static::$paths = $paths;

        static::preloadContainerDefinitions();
        static::loadConfigurations();
        static::loadUserModeDefinitions();

        /** @var Application $app */
        $app = $container->make(Application::class);
        $app->setContainer($container);

        return $app;
    }

    protected static function loadConfigurations()
    {
        $loaders = static::$config->readConfig('loaders/container.php');

        // read the app and dependencies configurations.
        static::$config->readMany(['app', 'dependencies']);

        // Loaders and Config classes do not require or use any particular
        // dependency injector. Instead, it uses LoaderReflector which
        // requires a symbol table.
        $symbolTable = new SymbolTable([
            ConfigFileReader::class       => ['type' => ConfigFileReader::class, 'value' => static::$config],
            // the current config class - at this point we don't know its class and don't care.
            get_class(static::$container) => ['type' => get_class(static::$container), 'value' => static::$container],
        ]);
        $symbolTable->setContainer(static::$container);

        // autoload configurations
        static::$loader = new LoaderSet('application', new LoaderReflector($symbolTable), static::$container);
        static::$loader->setSymbolTable($symbolTable);
        static::$loader->import($loaders);
        static::$loader->loadAll()->configure();
    }

    /**
     * This method depends on the 'dependencies' entry in the ConfigFileReader collection.
     */
    private static function loadUserModeDefinitions()
    {
        //$di = static::$container;
    }

    /**
     * Preload the framework container with base classes and aliases.
     *
     * The main classes instantiated or registered are:
     *
     *   'di'     => AurynDI,          # "rdlowrey/auryn" : "1.2"
     *   'config' => ConfigFileReader, # "oddgreg/nine-loaders" : "dev-master"
     *   'paths'  => Paths,            # "oddgreg/nine-collections" : "dev-master"
     *   'events' => EventDispatcher,  # "symfony/event-dispatcher" : "^3.1"
     *
     * @throws ContainerRequirementMismatchException
     */
    private static function preloadContainerDefinitions()
    {
        $di = static::$container;

        $di->alias(ContainerInterface::class, AurynDI::class);
        $di->alias(Injector::class, AurynDI::class);

        #@formatter:off
        $di->alias('config', ConfigFileReader::class);
        $di->alias('di',     AurynDI::class);
        $di->alias('paths',  Paths::class);
        #@formatter:on

        $di->share(static::$config);
        $di->share(static::$container);
        $di->share(static::$paths);
    }
}
