<?php
namespace BOF\Command;

use BOF\Repository\ProfileDataProviderI;
use BOF\Utils\ProfileDataHelperI;
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
     * @var ProfileDataHelperI
     */
    private $dataHelper;

    /**
     * ReportYearlyCommand constructor.
     * @param ProfileDataProviderI $dataProvider
     * @param ProfileDataHelperI $dataHelper
     */
    public function __construct(
        ProfileDataProviderI $dataProvider,
        ProfileDataHelperI $dataHelper
    ) {
        $this->dataProvider = $dataProvider;
        $this->dataHelper = $dataHelper;

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

        $data = $this->dataHelper->tableViewDataAssemble($profiles, $views);

        foreach ($data as $year => $formattedData) {

            $header = $this->dataHelper->getMonthsRange();
            array_unshift($header, sprintf('%s %d', 'Profile', $year));

            // Show data in a table - headers, data
            $io->table($header, $formattedData);
        }
    }
}
