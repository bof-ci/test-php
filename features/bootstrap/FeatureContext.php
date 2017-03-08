<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given that there is historical data available
     */
    public function thatThereIsHistoricalDataAvailable()
    {
        throw new PendingException();
    }

    /**
     * @When I execute the Yearly Views report
     */
    public function iExecuteTheYearlyViewsReport()
    {
        throw new PendingException();
    }

    /**
     * @Then I expect to see a monthly breakdown of the total views per profiles
     */
    public function iExpectToSeeAMonthlyBreakdownOfTheTotalViewsPerProfiles()
    {
        throw new PendingException();
    }

    /**
     * @When I view the Yearly Views report
     */
    public function iViewTheYearlyViewsReport()
    {
        throw new PendingException();
    }

    /**
     * @Then I expect to have the profiles names listed in alphabetical order
     */
    public function iExpectToHaveTheProfilesNamesListedInAlphabeticalOrder()
    {
        throw new PendingException();
    }

    /**
     * @When I expect to see :arg1 when data is not available
     */
    public function iExpectToSeeWhenDataIsNotAvailable($arg1)
    {
        throw new PendingException();
    }
}
