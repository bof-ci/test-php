<?php
namespace BOF\Command;

use BOF\Entity\Profile;
use BOF\Repository\DailyStatisticsViewsRepository;
use BOF\Repository\ProfilesRepository;
use BOF\Repository\ViewsRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends ContainerAwareCommand
{

    /**
     * Yearly report types
     */
    const TYPES = ['Profiles', 'Help'];

    /**
     * @var ProfilesRepository $profiles_repository
     */
    protected $profiles_repository;

    /**
     * @var DailyStatisticsViewsRepository $daily_views_repository
     */
    protected $daily_views_repository;

    protected function configure()
    {
        $this
            ->setName('report:yearly')
            ->addArgument('type', InputArgument::OPTIONAL, 'Type of the report', 'Help')
            ->setDescription('Reporting by year and type')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Setting database
        $this->profiles_repository = $this->getContainer()->get('ProfilesRepository');
        $this->daily_views_repository = $this->getContainer()->get('DailyStatisticsViewsRepository');

        $io = new SymfonyStyle($input,$output);

        if (!in_array($input->getArgument('type'), self::TYPES)) {
            throw new InvalidArgumentException(
                sprintf('Type must be one of the followings: %s',
                implode(', ', self::TYPES))
            );
        }

        // Argument: Help
        if ($input->getArgument('type') == 'Help') {
            $io->writeln('Usage: <command> <type>');
            $io->writeln('Types: ' . implode(', ', self::TYPES));
        }

        // Argument: Profiles
        if ($input->getArgument('type') == 'Profiles') { $this->getProfilesReport($io); }

    }

    /**
     * Returning with profiles table
     *
     * @param SymfonyStyle $io
     */
    public function getProfilesReport(SymfonyStyle $io) {

        $year = $this->yearQuestion($io);

        try {
            $profileAndYearPair = $this->daily_views_repository->searchByYearAndMonth($year);
        } catch (DBALException $e) {
            throw new \RuntimeException($e->getMessage());
        }

        $columns = ['Profile        '.$year];
        $rows = [];

        foreach ($profileAndYearPair as $key => $item) {

            foreach ($item as $columnName => $value) {

                if ($columnName == 'profile')
                    continue;

                // Adding columns, with capital first letters
                $columnToAdd = ucfirst($columnName);

                if (in_array($columnToAdd, $columns))
                    continue;

                $columns[] = $columnToAdd;
            }



            // Adding rows
            $rows[] = $item;

        }

        // Creating table
        $io->table($columns, $rows);


    }

    /**
     * @param SymfonyStyle $io
     * @return mixed
     */
    protected function yearQuestion(SymfonyStyle $io)
    {

        $currentYear = (new \DateTime())->format('Y');

        $question = $io->ask(sprintf('Enter a year to display (default: %d)', $currentYear), $currentYear, function ($number) {
            if (!is_numeric($number)) {
                throw new InvalidArgumentException('You must type a number.');
            }

            return (int) $number;
        });

        return $question;
    }
}
