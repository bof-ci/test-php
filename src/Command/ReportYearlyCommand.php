<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputArgument;
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
            ->addArgument('year', InputArgument::OPTIONAL, 'Year for the report')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);

        $profiles = $this->getProfiles();
        // Show data in a table - headers, data
        $io->table(['Profile'], $profiles);

        $monthlyViewsPerProfile = $this->getMonthlyBreakDownOfTotalViewsPerProfile(2017);
        $rows = [];
        foreach($monthlyViewsPerProfile as $profile)
        {
            $rows[] = [$profile['profile_id'], $profile['profile_name'], $profile['month'], $profile['views']];
        }
        $io->table(
            ['Profile Id', 'Profile Name', 'Month', 'Views'],
            $rows
        );

    }

    /**
     * @return mixed
     */
    public function getProfiles()
    {
        $db = $this->getContainer()->get('database_connection');
        $profiles = $db->query('select profile_name from profiles')->fetchAll();

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
     * @param $year
     * @return array
     */
    public function getMonthlyBreakDownOfTotalViewsPerProfile($y)
    {
//        return [];

        $db = $this->getContainer()->get('database_connection');

        $viewsPerProfile = $db->query(
            'select p.profile_id, p.profile_name, Month(v.date) as month, sum(v.views) as views
                from views as v
                join profiles as p
                on v.profile_id = p.profile_id
                where year(v.date) = :y
                group by p.profile_name
                order by p.profile_name asc', ['year' => $y]);

        return $viewsPerProfile;
    }




    public function getProfilesNamesListedInAlphabeticalOrder()
    {
        return [];
    }
}
