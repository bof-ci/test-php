<?php
use Symfony\Component\Console\Tester\CommandTester;
use BOF\Application;

final class YearlyViewsReport
{
    private $app;
    private $commandOutput;

    public function __construct()
    {
        $this->app = new Application();
    }

    /**
     * @param int $year
     */
    public function run($year=0)
    {
        $command = $this->app->find('report:profiles:yearly');
        $commandTester = new CommandTester($command);
        $executeOptions = array('command'  => $command->getName());
        if($year) {
            $executeOptions['--year'] = $year;
        }
        $commandTester->execute($executeOptions);

        // the output of the command in the console
        $this->commandOutput = $commandTester->getDisplay();
    }

    /**
     * @return string
     */
    public function output()
    {
        return $this->commandOutput;
    }


}
