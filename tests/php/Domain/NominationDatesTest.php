<?php 

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use Voting\Domain\DateFormat;
use Voting\Domain\NominationDates;
use Voting\Exception\DomainException;

class NominationDatesTest extends TestCase
{
    public function test_should_accept_start_and_end_dates()
    {
        $nominations = NominationDates::create(
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 00:00'),
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-03 00:00')
        );

        $this->assertEquals('2017-01-02 00:00', $nominations->start()->format(DateFormat::DEFAULT));
        $this->assertEquals('2017-01-03 00:00', $nominations->end()->format(DateFormat::DEFAULT));
    }

    public function test_should_have_start_date_before_end_date_message()
    {

        $this->setExpectedException(DomainException::class, 'The "Nomination start" date must be before the "Nomination end" date');

        $nominations = NominationDates::create(
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 01:00'),
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 00:59')
        );
    }

    public function test_should_not_accept_less_than_1_hour_range()
    {

        $this->setExpectedException(DomainException::class, 'Nominations must be at least 1 hour apart');

        $nominations = NominationDates::create(
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 00:00'),
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 00:59')
        );
    }

    public function test_should_allow_a_1_hour_or_more_range()
    {
        $nominations = NominationDates::create(
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 00:00'),
            Carbon::createFromFormat(DateFormat::DEFAULT, '2017-01-02 01:00')
        );

        $this->assertEquals('2017-01-02 00:00', $nominations->start()->format(DateFormat::DEFAULT));
        $this->assertEquals('2017-01-02 01:00', $nominations->end()->format(DateFormat::DEFAULT));
    }
}