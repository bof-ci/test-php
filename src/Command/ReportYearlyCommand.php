<?php
namespace BOF\Command;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Query\QueryException;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ReportYearlyCommand
 *
 * A command that retrieves a sum of monthly views for the specified year for
 * each profile. The result is output in a table.
 *
 * @package BOF\Command
 */
class ReportYearlyCommand extends ContainerAwareCommand
{
	/**
	 * Configuration for command report:profiles:yearly.
	 * Required arguments: year.
	 */
    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Yearly report of number of page views.')
			->addArgument('year', InputArgument::REQUIRED, 'Year of the report.')
        ;
    }

	/**
	 * Validate input arguments before executing the command.
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @throws RuntimeException
	 * @throws InvalidArgumentException
	 *
	 */
    protected function initialize(InputInterface $input, OutputInterface $output)
	{
		// If argument year is missing, throw new runtime exception.
		if (!$input->getArgument('year'))
			throw new RuntimeException('Missing required argument year.');
		// If argument year is non-numeric, throw new invalid argument exception.
		if (!is_numeric($input->getArgument('year')))
			throw new InvalidArgumentException('Year has to be a number.');
	}

	/**
	 * Execute the command.
	 *
	 * @param InputInterface  $input
	 * @param OutputInterface $output
	 *
	 * @return int|null|void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $db Connection */
        $io = new SymfonyStyle($input,$output);
        $db = $this->getContainer()->get('database_connection');

        // Get the argument year.
        $year = $input->getArgument('year');

        // Retrieve all the profile names.
        try {
			$profiles = $db->query('SELECT profile_name FROM profiles')->fetchAll();
		} catch (QueryException $exception) {
        	$output->writeln("Error retrieving profile names: $exception->getMessage()");
        	return $exception->getCode();
		}

		// Retrieve views data for the given year.
		try {
			$viewsSql = "SELECT * FROM views WHERE YEAR(date) = ?";
			$views = $db->executeQuery($viewsSql, array($year))->fetchAll();
		} catch (QueryException $exception) {
			$output->writeln("Error retrieving views information: $exception->getMessage()");
			return $exception->getCode();
		}

		// Prepare the array for summing up monthly views.
		$viewsMonthlyCount = [];
		for ($i = 0; $i < 5; $i++) {
			$viewsMonthlyCount[$i] = [];
			for ($j = 0; $j < 12; $j++) {
				// Mark start value as -1 since 0 views is valid.
				$viewsMonthlyCount[$i][$j] = -1;
			}
		}

		// Sum up the views for each month and profile.
		foreach ($views as $view) {
			$month = (int)explode('-', $view['date'])[1];
			// As soon as we encounter a month for this profile,
			// set the start value to 0 if it hasn't been already.
			// This way we don't mess up the sum by 1.
			if ($viewsMonthlyCount[$view['profile_id']-1][$month-1] == -1)
				$viewsMonthlyCount[$view['profile_id']-1][$month-1] = 0;
			$viewsMonthlyCount[$view['profile_id']-1][$month-1] += $view['views'];
		}

		// Store profile names and corresponding monthly number of views to the
		// output array. Also replace non existing records with "n/a".
		$profilesReport = [];
		for ($i = 0; $i < 5; $i++) {
			$profilesReport[$i] = [];
			$profilesReport[$i][0] = $profiles[$i]['profile_name'];
			$profilesReport[$i] = array_merge(
				$profilesReport[$i],
				preg_replace('/^-1/', 'n/a', $viewsMonthlyCount[$i])
			);
		}

		// Sort the output array in ascending order.
		array_multisort($profilesReport, SORT_ASC);

        // Show data in a table - headers, data
        $io->table(
        	[
        		"Profile $year", 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul',
				'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
			],
			$profilesReport
		);

        // Everything went fine - return 0.
        return 0;
    }
}
