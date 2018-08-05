<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Faker\Factory;
use Faker\Generator;
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
     * @var Connection
     */
    protected $db;


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
        $this->db = $this->getContainer()->get('database_connection');

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

        $dateTimeFormattedNow = (new \DateTime())->format('Y-m-d H:i:s');

        // Truncating the table in case we already ran the command
        $this->db->query('TRUNCATE views');

        $views = [];

        // Creating the views by profiles
        $profiles = $this->db->query('SELECT id, profile_name FROM profiles')->fetchAll();
        $progressProfiles = $io->createProgressBar(count($profiles));

        foreach ($profiles as $profile) {

            $profileId = $profile['id'];

            // Maximum 500 views per profile
            $dataPerDay = rand(0, 500);

            $io->newLine();
            $io->text('There will be '.$dataPerDay.' views generated for profile '.$profile['profile_name']);

            for ($i = 0; $i <= $dataPerDay; $i++) {

                // Random DateTime between the period
                $date = $this->faker->dateTimeBetween($startDate, $endDate);

                // Random browser data
                $browser = $this->faker->chrome;

                $views[] = [
                    'profile_id' => $profileId,
                    'date' => $date->format('Y-m-d H:i:s'),
                    'user_data' => ['browser' => $browser],
                    'created' => $dateTimeFormattedNow,
                    'updated' => $dateTimeFormattedNow
                ];


            }

            $progressProfiles->advance();
        }

        $progressInserting = $io->createProgressBar(count($views));

        // Inserting the data
        foreach ($views as $view) {
            $sql = sprintf(
                "INSERT INTO views (`profile_id`, `date`, `user_data`, `created`, `updated`) VALUES (%d, '%s', '%s', '%s', '%s')",
                $view['profile_id'],
                $view['date'],
                json_encode($view['user_data']),
                $view['created'],
                $view['updated']
            );
            $this->db->query($sql);
            $progressInserting->advance();
        }

        $io->newLine();

    }

}
