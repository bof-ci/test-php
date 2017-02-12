<?php
use Behat\Behat\Context\Context;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Gherkin\Node\PyStringNode;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $report;
    private $profileViews;

    /**
     * @BeforeSuite
     */
    public static function setup(BeforeSuiteScope $scope)
    {
        ProfileViews::setupDatabase();
    }

    /**
     * @AfterSuite
     */
    public static function teardown(AfterSuiteScope $scope)
    {
        ProfileViews::cleanDatabase();
    }

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->report = new YearlyViewsReport();
        $this->profileViews = new ProfileViews();
    }

    /**
     * @Given that there is historical data available for :year
     */
    public function thatThereIsHistoricalDataAvailableFor($year)
    {
        $this->profileViews->setDataExits($year);
    }

    /**
     * @When I execute the Yearly Views report for :year
     */
    public function iExecuteTheYearlyViewsReportFor($year)
    {
        $this->report->run($year);
    }

    /**
     * @Then I expect to see a monthly breakdown of the total views per profiles:
     */
    public function iExpectToSeeAMonthlyBreakdownOfTheTotalViewsPerProfiles(PyStringNode $string)
    {
        Assert::assertEquals($string->getRaw(), trim($this->report->output()));
    }

    /**
     * @Then I expect to have the profiles names listed in alphabetical order:
     */

    public function iExpectToHaveTheProfilesNamesListedInAlphabeticalOrder(PyStringNode $string)
    {
        Assert::assertEquals($string->getRaw(), trim($this->report->output()));
    }

    /**
     * @Then I expect to have the month names listed in chronological order:
     */
    public function iExpectToHaveTheMonthNamesListedInChronologicalOrder(PyStringNode $string)
    {
        Assert::assertEquals($string->getRaw(), trim($this->report->output()));
    }

    /**
     * @Then I expect to see the month name :abbreviatedMonthName
     */
    public function iExpectToSeeTheMonthName($abbreviatedMonthName)
    {
        Assert::assertContains($abbreviatedMonthName, $this->report->output());
    }

    /**
     * @Given that there is no historical data available for next year
     */
    public function thatThereIsNoHistoricalDataAvailableForNextYear()
    {
        $nextYear = date("Y")+1;
        $this->report->run($nextYear);
    }

    /**
     * @When I execute the Yearly Views report for next year
     */
    public function iExecuteTheYearlyViewsReportForNextYear()
    {
        $nextYear = date("Y")+1;
        $this->report->run($nextYear);
    }

    /**
     * @Given that there is no historical data available for current year
     */
    public function thatThereIsNoHistoricalDataAvailableForCurrentYear()
    {
        $this->profileViews->setDataExits(date('Y'));
    }

    /**
     * @When I execute the Yearly Views report
     */
    public function iExecuteTheYearlyViewsReport()
    {
        $this->report->run();
    }

    /**
     * @Then I expect to see n\/a
     */
    public function iExpectToSeeNA()
    {
        Assert::assertContains("n/a", $this->report->output());
    }

    /**
     * @Then I expect to see a monthly total views per profiles for January and February :year:
     */
    public function iExpectToSeeAMonthlyTotalViewsPerProfilesForJanuaryAndFebruary($year, PyStringNode $string)
    {
        Assert::assertContains($year, $this->report->output());
        Assert::assertEquals($string->getRaw(), trim($this->report->output()));
    }

    /**
     * @Then n\/a for months where data is not available:
     */
    public function nAForMonthsWhereDataIsNotAvailable(PyStringNode $string)
    {
        Assert::assertEquals($string->getRaw(), trim($this->report->output()));
    }

    /**
     * @Then views will have a comma separating every group of thousands:
     */
    public function viewsWillHaveACommaSeparatingEveryGroupOfThousands(PyStringNode $string)
    {
        Assert::assertEquals($string->getRaw(), trim($this->report->output()));
    }

    /**
     * @Then I expect to see year :year
     */
    public function iExpectToSeeYear($year)
    {
        Assert::assertContains($year, $this->report->output());
    }
}
