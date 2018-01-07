<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;

class ReportYearlyCommand extends ContainerAwareCommand
{
    private $year;

    public function setYear($year)
    {
        $this->year = $year;
    }

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->addArgument('year', InputArgument::REQUIRED, 'Year:');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        $year = $input->getArgument('year');
        if (!is_numeric($year)) {
            throw new \RuntimeException(
                    'Invalid Year argument'
            );
        };
        $this->setYear($year);

        $year_report = $this->getYearReport();

        $year_report = $this->createCalendar($this->structureReport($year_report));

        $io->table(['Profile', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], $year_report);

        // Show data in a table - headers, data
        //$io->table(['Profile'], $profiles);

    }

    private function getYearReport(): array
    {
        $db = $this->getContainer()->get('database_connection');

        return $db->query(
            'SELECT profiles.profile_name, SUM(views.views) as total_views, MONTH(views.date) as month 
              FROM profiles 
              LEFT JOIN views ON profiles.profile_id = views.profile_id 
              WHERE YEAR(views.date) = '.$this->year.' 
              GROUP BY profiles.profile_name, MONTH(views.date)
              ORDER BY profiles.profile_name'
            )->fetchAll();
    }

    private function structureReport(array $year_report): array
    {
        $table = array();

        foreach ($year_report as $row) {
            $table[$row['profile_name']][$row['month']] = number_format($row['total_views']);
        }

        return $table;
    }

    private function createCalendar(array $structured_report): array
    {
        $table = [];
        foreach ($structured_report as $key => $row) {
            $table[$key][0] = $key;
            for($i = 1; $i <= 12; $i++) {
                $table[$key][] = array_key_exists($i, $row) ? $row[$i] : 'n/a';
            }
        }
        return $table;
    }
}
