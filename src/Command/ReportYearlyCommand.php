<?php

namespace BOF\Command;

use BOF\AppBundle\BusinessAnalyst;
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
            ->setDescription('Page views report');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input, $output);
        $db = $this->getContainer()->get('database_connection');

        $profiles = $db->query(
            'SELECT 
                p.profile_id,
                p.profile_name as profile_name,
                DATE_FORMAT(v.date, \'%Y\') AS year,
                DATE_FORMAT(v.date, \'%m\') AS month,
                SUM(v.views) as views
            FROM
                profiles AS p
                    JOIN
                views AS v ON p.profile_id = v.profile_id
            GROUP BY p.profile_id , p.profile_name , DATE_FORMAT(v.date, \'%Y\') , DATE_FORMAT(v.date, \'%m\')
            ORDER BY p.profile_name , DATE_FORMAT(v.date, \'%Y\') , DATE_FORMAT(v.date, \'%m\')'
        )->fetchAll();

        $businessAnalyst = new BusinessAnalyst();

        $profiles = $businessAnalyst
            ->setResults($profiles)
            ->formatResult();

        for ($year = $businessAnalyst->minYear; $year <= $businessAnalyst->maxYear; $year++) {
            $head = [];
            $result = [];
            for ($m = BusinessAnalyst::JANUARY; $m <= BusinessAnalyst::DECEMBER; ++$m){
                $head[] = \DateTime::createFromFormat('!m', $m)->format('F');
            }
        }

            // Show data in a table - headers, data
//        $io->table($businessAnalyst->getHeader(), $profiles);
    }
}
