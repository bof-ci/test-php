<?php
namespace BOF\Command;

use BOF\Repository\ProfileDataProviderI;
use BOF\Utils\ProfileDataHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ReportYearlyCommand extends Command
{
    /**
     * @var ProfileDataProviderI
     */
    private $dataProvider;

    /**
     * ReportYearlyCommand constructor.
     * @param ProfileDataProviderI $dataProvider
     */
    public function __construct(ProfileDataProviderI $dataProvider)
    {
        $this->dataProvider = $dataProvider;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('report:profiles:yearly')
            ->setDescription('Page views report')
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
        $io = new SymfonyStyle($input,$output);

        $views = $this->dataProvider->getYearlyResult();
        $profiles = $this->dataProvider->getAllProfileNames();

        $dataFormatter = new ProfileDataHelper;
        $data = $dataFormatter->tableViewDataAssemble($profiles, $views);

        foreach ($data as $year => $formattedData) {

            $header = $dataFormatter->getMonthsRange();
            array_unshift($header, sprintf('%s %d', 'Profile', $year));

            // Show data in a table - headers, data
            $io->table($header, $formattedData);
        }
    }
}
