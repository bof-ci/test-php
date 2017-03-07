<?php

namespace spec\BOF\Command;

use BOF\Command\ReportYearlyCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReportYearlyCommandSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ReportYearlyCommand::class);
    }
}
