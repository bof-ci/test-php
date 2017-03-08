<?php

namespace spec\BOF\Command;

use BOF\Command\ReportYearlyCommand;
use \Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\Container;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReportYearlyCommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ReportYearlyCommand::class);
    }

    function it_should_return_an_array_of_five_profiles()
    {
        $this->getProfiles()->shouldHaveCount(6);
    }

    function it_should_return_an_array_of_a_monthly_breakdown_of_the_total_views_per_profiles()
    {
        $this->getMonthlyBreakDownOfTotalViewsPerProfile()->shouldHaveCount(0);
    }

    function it_should_return_an_array_the_profiles_names_listed_in_alphabetical_order()
    {
        $this->getProfilesNamesListedInAlphabeticalOrder()->shouldHaveCount(0);
    }

    function it_should_return_na_when_data_is_not_available()
    {
        $this->getProfilesNamesListedInAlphabeticalOrder()->shouldHaveCount(0);
    }
}
