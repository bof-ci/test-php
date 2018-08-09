<?php
namespace BOF\Command;

use BOF\Repository\ProfilesRepository;
use BOF\Repository\ViewsRepository;
use Doctrine\DBAL\DBALException;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Generating data for the views table
 * @package BOF\Command
 */
class TestDataResetCommand extends ContainerAwareCommand
{

    const COMMAND_CRON_GENERATE_STATISTICS = 'cron:generate:statistics';

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @var ViewsRepository
     */
    protected $views_repository;

    /**
     * @var ProfilesRepository
     */
    protected $profiles_repository;

    /**
     * Command config parameters and constructor
     * @throws \Exception
     */
    protected function configure()
    {
        $this
            ->setName('test:data:reset')
            ->setDescription('Reset MySQL data');

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->faker = Factory::create();
        $this->views_repository = $this->getContainer()->get('ViewsRepository');
        $this->profiles_repository = $this->getContainer()->get('ProfilesRepository');

        // Generating data for `views` table
        $this->generateViews($input, $output);

        $command = $this->getApplication()->find(self::COMMAND_CRON_GENERATE_STATISTICS);

        $arguments = [
            'command' => self::COMMAND_CRON_GENERATE_STATISTICS
        ];

        $statsInput = new ArrayInput($arguments);

        // Generating data for `statistics_daily_views`
        $command->run($statsInput, $output);

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function generateViews(InputInterface $input, OutputInterface $output)
    {

        $io = new SymfonyStyle($input, $output);
        $io->title('Generating views');

        // Period of data
        $startDate = (new \DateTime())->setDate(2014,9,1);
        $endDate = (new \DateTime())->setDate(2018, 8, 1);

        // Truncating the table in case we already ran the command
        $this->views_repository->query('TRUNCATE views');

        $views = [];

        try {
            // Creating the views by profiles
            $profiles = $this->profiles_repository->fetchAll('id, name');
            $progressProfiles = $io->createProgressBar(count($profiles));

            foreach ($profiles as $profile) {

                $profileId = $profile['id'];

                // Maximum 500 views per profile
                $dataPerDay = rand(0, 500);

                $io->newLine();
                $io->writeln('There will be '.$dataPerDay.' views generated for profile '.$profile['name']);

                for ($i = 0; $i <= $dataPerDay; $i++) {

                    // Random DateTime between the period
                    $date = $this->faker->dateTimeBetween($startDate, $endDate);

                    // Random browser data
                    $browser = $this->faker->chrome;

                    $views[] = [
                        'profile' => $profileId,
                        'date' => $date->format('Y-m-d H:i:s'),
                        'user_data' => json_encode(['browser' => $browser])
                    ];


                }

                $progressProfiles->advance();
            }

            $progressInserting = $io->createProgressBar(count($views));

            // Inserting the data
            foreach ($views as $view) {

                // Exception handling
                try {
                    $this->views_repository->insert($view);
                    $progressInserting->advance();
                } catch (DBALException $e) {
                    throw new \RuntimeException($e->getMessage());
                }

            }

            // New line at the end
            $io->newLine();

        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }

    }

}
