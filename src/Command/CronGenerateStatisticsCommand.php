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
 * Generating statistics
 * @package BOF\Command
 */
class CronGenerateStatistics extends ContainerAwareCommand
{

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
            ->setName('cron:generate:statistics')
            ->setDescription('Generate statistics');

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->db = $this->getContainer()->get('database_connection');

        // Generating data for `daily_statistics_views` table
        $this->generateDailyStatisticsViews($input, $output);

        // @TODO Generate Monthly statistics
        // @TODO Generate Yearly statistics

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function generateDailyStatisticsViews(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Generating statistics');

        // Truncating the table in case we already ran the command
        $this->db->query('TRUNCATE daily_statistics_views');

        $progressInserting = $io->createProgressBar(1);

        // First we need to fetch all the views
        $this->db->query('
        INSERT INTO daily_statistics_views
        
        (`profile_id`, `date`, `views`, `created`, `updated`) (
            SELECT
            
                -- Index 1
                views.profile_id as profile_id,
                
                -- Index 2
                DATE(views.date) as date,
                
                
                -- Data
                COUNT(views.id) as total_views,
                
                NOW() as created,
                
                NOW() as updated
                
            FROM views
            WHERE DATE(views.deleted) = "9999-12-31"
            GROUP BY views.profile_id, DATE(views.date)
        )
        ');

        $progressInserting->advance();

    }

}
