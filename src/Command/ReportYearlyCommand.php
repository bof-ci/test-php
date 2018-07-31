<?php

namespace BOF\Command;

use BOF\AppBundle\BusinessAnalyst;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $profiles = $this->getProfiles($year = $this->getUserYear($io));

        $businessAnalyst = new BusinessAnalyst();

        $profiles = $businessAnalyst
            ->setResults($profiles)
            ->formatResultPerProfiles();

        $table = new Table($output);
        $table
            ->setHeaders($businessAnalyst->getHeader());

        $row = [];
        foreach ($profiles as $peopleId => $profile) {
            array_unshift($profile, $businessAnalyst->getPerson($peopleId));
            $row[] = $profile;
        }

        $table->setRows($row);
        $table->render();
    }

    /**
     * getUserYear
     *
     * @param SymfonyStyle $io
     * @return string
     */
    private function getUserYear(SymfonyStyle $io)
    {
        $question = new Question('Please enter a year');
        $question->setValidator(function ($answer) {
            if (!\DateTime::createFromFormat('Y', $answer)) {
                throw new \LogicException(
                    'Incorrect year'
                );
            }

            return $answer;
        });

        $question->setMaxAttempts(5);

        return $io->askQuestion($question);
    }

    /**
     * getProfiles
     *
     * @param int $year
     * @return array
     * @throws \Exception
     */
    protected function getProfiles($year)
    {
        /** @var $db Connection */
        $db = $this->getContainer()->get('database_connection');
        /* @var \Doctrine\DBAL\Query\QueryBuilder $queryBuilder */
        $queryBuilder = $db->createQueryBuilder();

        $queryBuilder
            ->select(
                'p.profile_id',
                'p.profile_name AS profile_name',
                'DATE_FORMAT(v.date, \'%Y\') AS year',
                'DATE_FORMAT(v.date, \'%m\') AS month',
                'FORMAT(SUM(v.views), 0) AS views'
            )
            ->from('profiles', 'p')
            ->innerJoin('p', 'views', 'v', 'p.profile_id = v.profile_id')
            ->where('DATE_FORMAT(v.date, \'%Y\') = ?')
            ->groupBy('p.profile_id , p.profile_name , DATE_FORMAT(v.date, \'%Y\') , DATE_FORMAT(v.date, \'%m\')')
            ->orderBy('p.profile_name , DATE_FORMAT(v.date, \'%Y\') , DATE_FORMAT(v.date, \'%m\')')
            ->setParameter(0, $year);

        return $queryBuilder->execute()->fetchAll();
    }
}
