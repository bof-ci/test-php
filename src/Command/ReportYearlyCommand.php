<?php
namespace BOF\Command;

use BOF\Entities\ProfileEntity;
use BOF\Managers\ProfileManager;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument("year", InputArgument::OPTIONAL, "Year of report or last year in db")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var $db Connection
         * @var $em EntityManager
         */
        $io = new SymfonyStyle($input,$output);
        $year = $input->getArgument("year");

        $em = $this->getContainer()->get('entity_manager');
        $pm = new ProfileManager($em);

        $profiles = $pm->getProfiles();

        if($year === null){
            $year = $pm->getMaxYear();
        }
        $views = $pm->getSumViews($year);

        $headers = $this->getTableHeaders($profiles, $year);
        $rows = $this->getTableRows($profiles, $views);

        $io->table($headers, $rows);
    }

    /**
     * @param array $profiles
     * @param $year
     * @return array
     */
    private function getTableHeaders(array $profiles, $year)
    {
        $title = "Profile";
        $maxProfileNameLength = $this->getMaxProfileNameLength($profiles);
        $padLength = $maxProfileNameLength - strlen($title);
        $headers = [$title . str_pad($year, $padLength, " ", STR_PAD_LEFT)];
        for($c = 1; $c <= 12; $c++) {
            $date = \DateTime::createFromFormat('!m', $c);
            $headers[] = $date->format('M');
        }

        return $headers;
    }

    /**
     * @param array $profiles
     * @param array $views
     * @return array
     */
    private function getTableRows(array $profiles, array $views)
    {
        $profileViews = $this->getViewsPerProfile($views);
        $rows = [];
        for($c=0; $c<count($profiles); $c++){
            $profile = $profiles[$c];
            $profileId = $profile["id"];
            $row = [$profile["name"]];
            for($b=1; $b<=12; $b++){
                $row[] = isset($profileViews[$profileId]) && isset($profileViews[$profileId][$b])
                    ? number_format($profileViews[$profileId][$b]) : "n/a";
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @param array $profiles
     * @return mixed
     */
    private function getMaxProfileNameLength(array $profiles)
    {
        $names = array_reduce($profiles, function($carry, $item){ $carry[] = $item["name"]; return $carry; }, []);
        $lengths = array_map('strlen', $names);

        return max($lengths);
    }

    /**
     * @param array $views
     * @return array
     */
    private function getViewsPerProfile(array $views)
    {
        $profileViews = [];
        foreach ($views as $view){
            if(!isset($profileViews[$view["profile_id"]])){
                $profileViews[$view["profile_id"]] = [];
            }
            if(!isset($profileViews[$view["profile_id"]][$view["month"]])){
                $profileViews[$view["profile_id"]][$view["month"]] = [];
            }
            $profileViews[$view["profile_id"]][$view["month"]] = $view["views"];
        }

        return $profileViews;
    }
}
