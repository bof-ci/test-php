<?php
namespace BOF;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use DoctrineExtensions\Query\Mysql\Month;
use DoctrineExtensions\Query\Mysql\Year;

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
     * @param string $name    The name of the application
     * @param string $version The version of the application
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

        $this->setEntityManager();
    }

    /**
     * @return Command[] An array of default Command instances
     */
    protected function getConfiguredCommands()
    {
        $commands = [];
        foreach ($this->container->findTaggedServiceIds('console.command') as $commandId => $command) {
            $commands[] = $this->container->get($commandId);
        }
        return $commands;
    }

    protected function setEntityManager()
    {
        $conn = $this->container->get('database_connection');
        $config = $this->container->get('orm_configuration');
        $config->addCustomDatetimeFunction("MONTH", Month::class);
        $config->addCustomDatetimeFunction("YEAR", Year::class);

        $em = EntityManager::create($conn, $config);
        $this->container->set('entity_manager', $em);
    }

    /**
     * @return ContainerBuilder
     */
    public function getContainer()
    {
        return $this->container;
    }
}