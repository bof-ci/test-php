<?php
namespace BOF;

use BOF\Repository\DailyStatisticsViewsRepository;
use BOF\Repository\ProfilesRepository;
use BOF\Repository\ViewsRepository;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class Application
 * @package BOF
 */
class Application extends ConsoleApplication
{
    /**
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * Application constructor.
     * @param string $name
     * @param string $version
     * @throws \Exception
     */
    public function __construct($name = 'app', $version = '1')
    {
        $this->container = new ContainerBuilder();
        $loader = new YamlFileLoader($this->container, new FileLocator(__DIR__.'/../app'));
        $loader->load('config.yml');
        $loader->load('services.yml');

        // Initiate app
        parent::__construct($name, $version);

        // Add configured commands
        foreach ($this->getConfiguredCommands() as $command) {
            $this->add($command);
        }

        // Adding other dependencies
        $this->container->register('DailyStatisticsViewsRepository', DailyStatisticsViewsRepository::class);
        $this->container->register('ProfilesRepository', ProfilesRepository::class);
        $this->container->register('ViewsRepository', ViewsRepository::class);
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function getConfiguredCommands()
    {
        $commands = [];
        foreach ($this->container->findTaggedServiceIds('console.command') as $commandId => $command) {
            $commands[] = $this->container->get($commandId);
        }
        return $commands;
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }
}