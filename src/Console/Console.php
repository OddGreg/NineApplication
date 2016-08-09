<?php namespace Nine\Application\Console;

use Nine\Collections\ConfigInterface;
use Nine\Collections\Paths;
use Nine\Library\Lib;
use Nine\Loaders\ConfigFileReader;
use Nine\Loaders\Support\ClassFinder;
use Symfony\Component\Console\Application;

/**
 * @package Nine
 * @version 0.4.2
 * @author  Greg Truesdell <odd.greg@gmail.com>
 */
class Console extends Application
{
    /** @var array */
    protected $appCommands = [];

    /** @var ConfigInterface */
    protected $config;

    /** @var array */
    protected $frameworkCommands = [];

    /** @var Paths */
    protected $paths;

    /**
     * Console constructor.
     *
     * @param ConfigInterface|ConfigFileReader $config
     * @param Paths                            $paths
     * @param string                           $version
     */
    public function __construct(ConfigFileReader $config, Paths $paths, $version = \Nine\Application\Application::VERSION)
    {
        $this->config = $config;
        $this->paths = $paths;

        // the parent is a hijacked copy of the illuminate console application.
        // we hijacked it mainly to override a few properties - such as the title.
        parent::__construct($config['app.name'], $version);

        //$this->bootSettings();
        $this->configureEnvironment();

        // in all cases, register the framework commands
        $this->registerFrameworkCommands();
    }

    /**
     * @return array
     */
    public function getAppCommands()
    {
        return $this->appCommands;
    }

    /**
     * @return array
     */
    public function getFrameworkCommands()
    {
        return $this->frameworkCommands;
    }

    /**
     * Accepts a single-dimension array of Command class names.
     *
     * @param array $commandList
     */
    public function registerAppCommands(array $commandList)
    {
        foreach ($commandList as $command) {
            $this->appCommands[] = Lib::get_class_name($command);
            $this->add(new $command);
        }
    }

    /**
     * Registers an array of application mode Command class names.
     *
     * Reads all classes located in the given path, so only Commands
     * should be the only classes in the path.
     *
     * @param string $commandPath
     */
    public function registerAppCommandsIn(string $commandPath)
    {
        $commands = $this->registerCommandsIn($commandPath);

        // this may not be the only call to register application mode commands,
        // so merge with the app commands array.
        $this->appCommands = array_merge($this->appCommands, $commands);
    }

    /**
     *
     */
    private function configureEnvironment()
    {

    }

    /**
     * Registers any commands found the the provided path.
     *
     * Note: All classes found in the folder are loaded, so don't put anything
     *       but commands there.
     *
     * @param string $commandPath
     *
     * @return array
     */
    private function registerCommandsIn(string $commandPath) : array
    {
        $commands = [];

        foreach ((new ClassFinder)->findClasses($commandPath) as $command) {
            $commands[] = Lib::get_class_name($command);
            $this->add(new $command);
        }

        return $commands;
    }

    /**
     *  Registers the framework commands found in the framework Console\Commands path.
     */
    private function registerFrameworkCommands()
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'Commands/';
        $this->frameworkCommands = $this->registerCommandsIn($path);
    }

}
