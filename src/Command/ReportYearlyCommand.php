<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);

        $profiles = $this->getProfiles();

        // Show data in a table - headers, data
        $io->table(['Profile'], $profiles);

    }

    /**
     * @return mixed
     */
    public function getProfiles()
    {
//        $db = $this->getContainer()->get('database_connection');

//        $profiles = $db->query('SELECT profile_name FROM profiles')->fetchAll();

        $profiles = [
                        ["profile_name" => "Karl Lagerfeld"],
                        ["profile_name" => "Anna Wintour"],
                        ["profile_name" => "Tom Ford"],
                        ["profile_name" => "Tom Ford"],
                        ["profile_name" => "Pierre Alexis Dumas"],
                        ["profile_name" => "Sandra Choi"]
                    ];

        return $profiles;
    }

    /**
     * Get monthly breakdown of total views per profile.
     * 
     * @return array
     */
    public function getMonthlyBreakDownOfTotalViewsPerProfile()
    {
        return [];
    }
}
