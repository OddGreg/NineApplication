<?php

/**
 * @package Nine Application
 * @version 0.5.0
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */

use Nine\Application\Application;
use Nine\Application\Containers\Contracts\ContainerCompatibilityInterface;
use Nine\Collections\Paths;
use Nine\Loaders\ConfigFileReader;
use Nine\Loaders\Exceptions\ConfigurationFileNotFound;
use Nine\Loaders\Exceptions\InvalidConfigurationImportValueException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\RouteCollection;

/**
 * The App class is a singleton that provides direct access to
 * core configuration and process classes.
 *
 * It is only used by the Support/helpers.php helper file and should
 * not be modified or extended for the purpose of providing a
 * service locator.
 *
 * Only the following framework assets are exposed:
 *      method      class
 *      ----------- ------------------
 *      app()       Application
 *      config()    ConfigFileReader
 *      container() ContainerCompatibilityInterface
 *      path()      Paths
 *
 * The Support/helper.php exposes the following utility functions:
 *
 *      app(), config(), di(), path()
 *
 */
final class App
{
    /** @var Application $app */
    protected static $app;

    /** @var ConfigFileReader $config */
    protected static $config;

    /** @var ContainerCompatibilityInterface $container */
    protected static $container;

    /** @var EventDispatcher $dispatcher */
    protected static $dispatcher;

    /** @var static $instance */
    protected static $instance;

    /** @var Paths $paths */
    protected static $paths;

    /** @var RouteCollection $routes */
    protected static $routes;

    /**
     * App constructor.
     *
     * @param Application $app
     */
    private function __construct(Application $app)
    {
        // Because this is a helper class with a singular and confined purpose,
        // we will assert firmly that only one instance is allowed.
        // May your best practices antennas forever twitch unnoticed.
        if (NULL === static::$instance) {

            $attr = $app->getGlobals();

            static::$instance = $this;
            static::$app = $app;
            static::$config = $attr['config'];
            static::$container = $attr['container'];
            static::$dispatcher = $attr['dispatcher'];
            static::$paths = $attr['paths'];
            static::$routes = $attr['routes'];
        }
    }

    /**
     * @return Application
     */
    public static function app(): Application
    {
        return self::$app;
    }

    /**
     * Provided for access to configuration files and values.
     *
     * @param string     $compoundKey
     * @param null|mixed $default
     *
     * @return ConfigFileReader
     * @throws InvalidConfigurationImportValueException
     * @throws ConfigurationFileNotFound
     * @throws \InvalidArgumentException
     */
    public static function config(string $compoundKey = NULL, $default = NULL)
    {
        return $compoundKey
            ? static::$config->has($compoundKey) ? static::$config->read($compoundKey) : $default
            : static::$config;
    }

    /**
     * @return ContainerCompatibilityInterface
     */
    public static function container()
    {
        return static::$container;
    }

    /**
     * @param Application $app
     *
     * @return static
     */
    public static function createFromApplication(Application $app)
    {
        return static::$instance ?: new static($app);
    }

    /**
     * @return EventDispatcher
     */
    public static function dispatcher(): EventDispatcher
    {
        return self::$dispatcher;
    }

    /**
     * @param string $path
     *
     * @return mixed|Paths
     */
    public static function path(string $path = NULL)
    {
        return $path ? static::$paths[$path] : static::$paths;
    }

    public static function reset()
    {
        static::$instance = NULL;

        static::$app = NULL;
        static::$config = NULL;
        static::$container = NULL;
        static::$paths = NULL;
    }

    /**
     * @return RouteCollection
     */
    public static function routes(): RouteCollection
    {
        return self::$routes;
    }

    /**
     * @param Application $app
     */
    public static function setApplication(Application $app)
    {
        new static($app);
    }

}
