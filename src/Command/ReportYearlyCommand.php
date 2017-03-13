<?php
namespace BOF\Command;

use DateTime;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{
    const YEAR = 'year';

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument(self::YEAR, InputArgument::OPTIONAL)
                ])
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);

        $currentDate = new DateTime('now');

        $year = $input->getArgument(self::YEAR)? $input->getArgument(self::YEAR) : $currentDate->format('Y');
        $db = $this->getContainer()->get('database_connection');

        $data = $db->query('SELECT p.profile_name as `name` , MONTH(v.`date`) as `month`, SUM(v.views) as `views` FROM profiles p LEFT JOIN views v ON v.profile_id = p.profile_id WHERE YEAR(v.`date`) = '.$year.' GROUP BY MONTH(v.`date`), p.profile_id ORDER BY p.profile_name ASC, v.`date`')->fetchAll();

        $mapped = [];

        foreach ($data as $row) {
            $mapped[$row['name']][$row['month']] = $row['views'];
        }

        $profiles = array_values(array_unique(array_column($data, 'name')));

        foreach ($profiles as $k => $profile) {
            $report[$k][0]=$profile;
            for ($i=1; $i<=12; $i++) {
                if (array_key_exists($i, $mapped[$profile])) {
                    $report[$k][$i]= $mapped[$profile][$i];
                } else {
                    $report[$k][$i]= 'n/a';
                }
            }

        }

        // Show data in a table - headers, data
        $io->table(['Profile '.$year, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], $report);

    }
}
