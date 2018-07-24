<?php 

use PHPUnit\Framework\TestCase;
use Voting\Domain\VotingSchedule;
use Voting\Domain\DateRange;
use Voting\Domain\NominationDates;
use Voting\Domain\VotingDates;
use Voting\Exception\DomainException;
use Carbon\Carbon;


/**
 * Tests voting schedule logic
 * @author Gemma Black <gblackuk@gmail.com>
 * @todo test the 1 hour apart check
 */
class VotingScheduleTest extends TestCase
{


    /**
     * Tests that the voting schedule value object can be instantiated
     * @return void
     */
    public function test_Voting_Schedule_Can_Be_Created()
    {
        $nominationStartDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');
        $nominationEndDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 10:00');
        $votingStartDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 10:00');
        $votingEndDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 11:00');

        $schedule = VotingSchedule::create(
            NominationDates::create($nominationStartDate, $nominationEndDate),
            VotingDates::create($votingStartDate, $votingEndDate)
        );

        $this->assertEquals($schedule->nominations(), NominationDates::create($nominationStartDate, $nominationEndDate));
        $this->assertEquals($schedule->voting(), VotingDates::create($votingStartDate, $votingEndDate));
    }


    /**
     * Tests an TypeError exception is thrown if DateRange instances aren't passed in as
     * arguments
     * @return void
     */
    public function testVotingScheduleThrowsExceptionIfNotPassedInDateRanges()
    {
        $nominationStartDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');
        $nominationEndDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');
        $votingStartDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');
        $votingEndDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');

        $this->setExpectedException(TypeError::class);

        $schedule = VotingSchedule::create(
            (object) [$nominationStartDate, $nominationEndDate],
            (object) [$votingStartDate, $votingEndDate]
        );
    }

    /**
     * Tests if voting can start the same time that nominations end
     * @return void
     */
    public function testVotingScheduleSucceedsIfVotingStartDateIsOnNominationEndDate()
    {
        $nominationStartDate = Carbon::createFromFormat('Y-m-j H:i', '2017-11-01 09:00');
        $nominationEndDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');
        $votingStartDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');
        $votingEndDate = Carbon::createFromFormat('Y-m-j H:i', '2018-01-01 09:00');

        $schedule = VotingSchedule::create(
            NominationDates::create($nominationStartDate, $nominationEndDate),
            VotingDates::create($votingStartDate, $votingEndDate)
        );

        $this->assertEquals($schedule->nominations(), NominationDates::create($nominationStartDate, $nominationEndDate));
        $this->assertEquals($schedule->voting(), VotingDates::create($votingStartDate, $votingEndDate));
    }

    /**
     * Tests if voting can start way after nominations end
     * @return void
     */
    public function testVotingCanStartLongAfterNominationsEnd()
    {
        $nominationStartDate = Carbon::createFromFormat('Y-m-j H:i', '2017-11-01 09:00');
        $nominationEndDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');
        $votingStartDate = Carbon::createFromFormat('Y-m-j H:i', '2018-12-01 09:00');
        $votingEndDate = Carbon::createFromFormat('Y-m-j H:i', '2019-01-01 09:00');

        $schedule = VotingSchedule::create(
            NominationDates::create($nominationStartDate, $nominationEndDate),
            VotingDates::create($votingStartDate, $votingEndDate)
        );

        $this->assertEquals($schedule->nominations(), NominationDates::create($nominationStartDate, $nominationEndDate));
        $this->assertEquals($schedule->voting(), VotingDates::create($votingStartDate, $votingEndDate));
    }
}