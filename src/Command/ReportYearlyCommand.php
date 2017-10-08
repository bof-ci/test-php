<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{
    private $year;
    private $order;

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
        ;
    }

    /**
     * Ask the user for values and validate them
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        // Ask For Year
        $helper = $this->getHelper('question');
        $question = new Question('Enter year, default is current year:', date('Y'));
        $question->setValidator(function ($year) {
            $year = trim($year);
            if (!is_numeric($year) || strlen($year) != 4) {
                throw new \RuntimeException(
                    'Year is not in correct format'
                );
            }

            return $year;
        });
        $this->year = $helper->ask($input, $output, $question);

        // Ask For Order
        $question = new Question('Sort by name ASC or DESC, default ASC:', 'ASC');
        $question->setValidator(function ($order) {
            $order = strtoupper(trim($order));
            if ($order != 'ASC' && $order != 'DESC') {
                throw new \RuntimeException(
                    'Sort option you entered is not correct'
                );
            }

            return $order;
        });
        $this->order = $helper->ask($input, $output, $question);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            /** @var $db Connection */
            $io = new SymfonyStyle($input,$output);

            $yearlyData = $this->fetchYearlyData();
            if(!count($yearlyData)) {
                $io->text("No Data found for year ". $this->year);
                exit();
            }

            $tableRows = $this->getTableRows($this->getGroupData($yearlyData));
            $tableHeaders = ['Profile '. $this->year, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Avg', 'Sep', 'Oct', 'Nov', 'Dec'];
            $io->table($tableHeaders, $tableRows);

        }
        catch (\Error $e) {
            throw new \RuntimeException(
                'Line:'. $e->getLine() .' '. $e->getMessage()
            );
        }
    }

    /**
     * Fetch DB Yearly Data
     *
     * @return mixed
     */
    private function fetchYearlyData(): array
    {
        $db = $this->getContainer()->get('database_connection');
        return $db->query(
            "SELECT profiles.profile_name, SUM(views.views) as results, MONTH(views.date) as month 
              FROM profiles 
              LEFT JOIN views ON profiles.profile_id = views.profile_id 
              WHERE YEAR(views.date) = {$this->year} 
              GROUP BY profiles.profile_name, MONTH(views.date)
              ORDER BY profiles.profile_name {$this->order}")->fetchAll();
    }

    /**
     * Group Query Data By Profile Name
     *
     * @param array $yearlyData
     * @return array
     */
    private function getGroupData(array $yearlyData): array {
        $groupedData = [];
        $profileNames = [];
        foreach ($yearlyData as $item) {
            if(!in_array($item['profile_name'], $profileNames))
                array_push($profileNames, $item['profile_name']);

            $index = array_search($item['profile_name'], $profileNames);
            // First Key Is Profile Name
            if(!count($groupedData[$index]))
                $groupedData[$index][] = $item['profile_name'];

            // Results
            $groupedData[$index][$item['month']] = $item['results'];
        }

        return $groupedData;
    }

    /**
     * Generate Array for Table Rows
     *
     * @param array $groupedData
     * @return array
     */
    private function getTableRows(array $groupedData): array {
        $tableRows = [];
        foreach ($groupedData as $key => $row) {
            for($i = 0; $i <= 12; $i++) {
                $tableRows[$key][] = array_key_exists($i, $row) ? $row[$i] : 'n/a';
            }
        }

        return $tableRows;
    }
}
