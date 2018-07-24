<?php 

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use Voting\Domain\DateFormat;
use Voting\Domain\VotingDates;
use Voting\Exception\DomainException;

class VotingDatesTest extends TestCase
{
    public function test_should_accept_start_and_end_dates()
    {
        $voting = VotingDates::create(
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 00:00'),
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-03 00:00')
        );

        $this->assertEquals('2017-01-02 00:00', $voting->start()->format(DateFormat::DEFAULT));
        $this->assertEquals('2017-01-03 00:00', $voting->end()->format(DateFormat::DEFAULT));
    }

    public function test_should_not_accept_less_than_1_hour_range()
    {

        $this->setExpectedException(DomainException::class, 'Voting phase must be at least 1');

        VotingDates::create(
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 00:00'),
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 00:59')
        );
    }

    public function test_should_allow_a_1_hour_or_more_range()
    {
        $voting = VotingDates::create(
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 00:00'),
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 01:00')
        );

        $this->assertEquals('2017-01-02 00:00', $voting->start()->format(DateFormat::DEFAULT));
        $this->assertEquals('2017-01-02 01:00', $voting->end()->format(DateFormat::DEFAULT));
    }
}