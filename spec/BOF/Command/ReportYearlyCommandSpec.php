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


}
