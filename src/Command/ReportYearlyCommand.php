<?php
namespace BOF\Command;

use BOF\Entity\Profile;
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
     * @var EntityManager
     */
    protected $em;

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
        $em = $this->getContainer()->get('database_connection');

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
        if ($input->getArgument('type') == 'Profiles') {

            $this->yearQuestion($io);

            $profiles = $this->em->getRepository(Profile::class)->findAll();

            // Show data in a table - headers, data
            $io->table(['Profile'], $profiles);

        }

    }

    /**
     * @param SymfonyStyle $io
     * @return string
     */
    protected function yearQuestion(SymfonyStyle $io) {

        $currentYear = (new \DateTime())->format('Y');

        $question = new Question(sprintf('Enter a year to display (default: %d)',  $currentYear), $currentYear);

        $question->setValidator(function ($answer) {
            if (!(\DateTime::createFromFormat('Y', $answer))) {
                throw new RuntimeException('Invalid year');
            }
        });

        return $io->askQuestion($question);

    }
}
