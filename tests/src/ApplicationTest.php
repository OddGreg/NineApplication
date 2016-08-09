<?php namespace Nine\Application;

use Nine\Application\Containers\AurynDI;
use Nine\Collections\Paths;
use Nine\Loaders\ConfigFileReader;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\RouteCollection;

/**
 * Test the Collection Class
 *
 * @backupGlobals          disabled
 * @backupStaticAttributes disabled
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /** @var Application */
    protected $app;

    public function setUp()
    {
        // The Application class instantiate the \App class which is
        // a persistent singleton. reset() de-registers the class to
        // reset internal instances.
        \App::reset();

        $this->app = new Application(
            (new ConfigFileReader(CONFIG))->preloadPath(),
            new EventDispatcher(),
            new RouteCollection(),
            new Paths(include __DIR__ . '/../support/paths.php')
        );

        $this->app->setContainer(new AurynDI());
    }

    public function testAppInstance()
    {
        static::assertInstanceOf(Application::class, $this->app,
            'The Application is expected to have been instantiated without error.');

        static::assertInstanceOf(ConfigFileReader::class, $this->app->getGlobals()['config'],
            'The Application is expected to have instantiated the ConfigFileReader class.');

        static::assertInstanceOf(AurynDI::class, $this->app->getContainer(),
            'The Application is expected to have a reference to the dependency injector.');
    }

    public function testHelperClassFunctions()
    {
        // note that the app() functions DOES NOT operate like it does in some
        // frameworks. This returns a non-container-wrapping application instance.
        static::assertSame($this->app, \App::app(),
            'The instantiated app and that referenced by the helper app() function must be the same.');

        static::assertInstanceOf(Paths::class, $this->app->getGlobals()['paths'],
            'The Path instance should have been set.');
        static::assertSame($this->app->getGlobals()['paths'], \App::path(),
            'The instantiated app->path and that referenced by the helper path() function must be the same.');

        static::assertInstanceOf(AurynDI::class, $this->app->getGlobals()['container'],
            'The container instance should have been set.');
        static::assertSame($this->app->getGlobals()['container'], \App::container(),
            'The instantiated app->di and that referenced by the helper di() function must be the same.');

        static::assertInstanceOf(ConfigFileReader::class, $this->app->getGlobals()['config'],
            'The ConfigFileReader instance should have been set.');
        static::assertSame($this->app->getGlobals()['config'], \App::config(),
            'The instantiated app->config and that referenced by the helper config() function must be the same.');

        static::assertInstanceOf(EventDispatcher::class, $this->app->getGlobals()['dispatcher'],
            'The EventDispatcher instance should have been set.');
        static::assertSame($this->app->getGlobals()['dispatcher'], \App::dispatcher(),
            'The instantiated app->event-dispatcher and that referenced by the helper dispatcher() function must be the same.');

    }

    public function testRequests()
    {
    }

}
