<?php

use PHPUnit\Framework\TestCase;
use Voting\Domain\Award;
use Voting\Domain\NominationDates;
use Voting\Domain\AwardTitle;
use Voting\Domain\AwardYear;
use Voting\Domain\CurrentTime;
use Voting\Domain\VotingSchedule;
use Voting\Domain\VotingDates;
use Voting\Domain\DateFormat;
use Voting\Exception\DomainException;
use VotingMocks\MockCurrentTime;
use Carbon\Carbon;

/**
 * Award domain tests
 * @author Gemma Black <gblackuk@gmail.com>
 */
class AwardTest extends TestCase
{

    protected $nomStart;
    protected $nomEnd;
    protected $votingStart;
    protected $votingEnd;
    protected $awardTitle = 'Some Test Award';
    protected $awardYear = '2018';
    protected $pastVotingSchedule;
    protected $currentTime;

    public function setUp()
    {   
        $this->nomStart = Carbon::now();
        $this->nomEnd = Carbon::now()->addHours(24);
        $this->votingStart = Carbon::now()->addMonth(1);
        $this->votingEnd = Carbon::now()->addMonth(1)->addHours(24);
        $this->pastVotingSchedule = new VotingSchedule(
            NominationDates::create(Carbon::now()->subDays(10), Carbon::now()->subDays(9)),
            VotingDates::create(Carbon::now()->subDays(2), Carbon::now()->subDays(1))
        );

        $stub = $this->createMock(CurrentTime::class);
    }


    /**
     * Testing that an award can created with essential parameters/dependencies
     *
     * @return void
     */
    public function test_should_be_created_with_essential_parameters()
    {
        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($this->awardYear),
            new VotingSchedule(
                NominationDates::create($this->nomStart, $this->nomEnd),
                VotingDates::create($this->votingStart, $this->votingEnd)
            )
        );

        $this->assertEquals($this->awardTitle, $award->title()->string());
        $this->assertEquals($this->awardYear, $award->year()->string());
        $this->assertEquals($this->nomStart, $award->schedule()->nominations()->start());
        $this->assertEquals($this->nomEnd, $award->schedule()->nominations()->end());
        $this->assertEquals($this->votingStart, $award->schedule()->voting()->start());
        $this->assertEquals($this->votingEnd, $award->schedule()->voting()->end());
    }


    /**
     * Tests that a type error occurs unless the correct value object is passed
     *
     * @return void
     */
    public function test_must_have_award_title_as_value_object()
    {
        $this->expectException(TypeError::class);

        Award::start(
            $this->awardTitle,
            new AwardYear($this->awardYear),
            new VotingSchedule(
                NominationDates::create($this->nomStart, $this->nomEnd),
                VotingDates::create($this->votingStart, $this->votingEnd)
            )
        );
    }


    /**
     * Tests that a type error occurs if not an award year value object
     *
     * @return void
     */
    public function test_must_have_award_year_as_value_object()
    {
        $this->expectException(TypeError::class);

        Award::start(
            new AwardTitle($this->awardTitle),
            '2018',
            new VotingSchedule(
                NominationDates::create($this->nomStart, $this->nomEnd),
                VotingDates::create($this->votingStart, $this->votingEnd)
            )
        );
    }


    /**
     * Tests that a type error occurs if schedule is not a schedule value object
     *
     * @return void
     */
    public function test_must_have_schedule_as_value_object()
    {
        $this->expectException(TypeError::class);

        Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($this->awardYear),
            'some random schedule'
        );
    }


    /**
     * Tests the award can go live
     *
     * @return void
     */
    public function test_can_go_live()
    {
        $award = Award::hydratable();
        $updated = Award::goLive($award);
        $this->assertTrue($updated->isLive());
    }


    /**
     * Tests archiving event fails if winners have not been announced
     *
     * @return void
     */
    public function test_archive_is_not_allowed_if_winners_have_not_been_announced()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Award cannot be archived before winners have been announced');

        $award = Award::hydratable();
        $updated = Award::archive($award, CurrentTime::set());
    }


    public function test_archiving_future_award_year()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('You cannot archive an award for a future year. We have not reached 2080 yet');

        $year2018 = Carbon::create(2018, 9, 3, 10, 30);
        $year2080 = Carbon::create(2080, 9, 3, 10, 30);

        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($year2080->year),
            new VotingSchedule(
                NominationDates::create($year2080->copy()->subDays(20), $year2080->copy()->subDays(10)),
                VotingDates::create($year2080->copy()->addDays(10), $year2080->copy()->addDays(20))
            )
        );

        $award = Award::publishWinners($award, $year2080->copy()->addDays(30));

        Award::archive($award, MockCurrentTime::setMock($year2018));
    }

    public function test_archiving_allows_current_award_year()
    {
        $year2018 = Carbon::create(2018, 9, 3, 10, 30);

        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($year2018->year),
            new VotingSchedule(
                NominationDates::create($year2018->copy()->subDays(20), $year2018->copy()->subDays(10)),
                VotingDates::create($year2018->copy()->addDays(10), $year2018->copy()->addDays(20))
            )
        );

        $award = Award::publishWinners($award, $year2018->copy()->addDays(30));

        $award = Award::archive($award, MockCurrentTime::setMock(Carbon::create(2018)));

        $this->assertEquals($this->awardTitle, $award->title()->string());
    }

    public function test_archiving_allows_previous_award_year()
    {
        $year2018 = Carbon::create(2018, 9, 3, 10, 30);

        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($year2018->year),
            new VotingSchedule(
                NominationDates::create($year2018->copy()->subDays(20), $year2018->copy()->subDays(10)),
                VotingDates::create($year2018->copy()->addDays(10), $year2018->copy()->addDays(20))
            )
        );

        $award = Award::publishWinners($award, $year2018->copy()->addDays(30));

        $award = Award::archive($award, MockCurrentTime::setMock(Carbon::create(2019)));

        $this->assertEquals($this->awardTitle, $award->title()->string());
    }

    /**
     * Tests archiving 
     *
     * @return void
     */
    public function test_archiving_award()
    {
        $award = Award::hydratable();
        $award->__set('schedule', $this->pastVotingSchedule); // Cheated without hydrator
        $updated = Award::publishWinners($award, Carbon::create(2019));
        $updated = Award::archive($updated, MockCurrentTime::setMock(Carbon::create(2019)));
        $this->assertTrue($updated->isArchived());
    }


    /**
     * Tests going live toggles of award archive
     *
     * @return void
     */
    public function test_go_live_toggles_off_archive()
    {
        $award = Award::hydratable();
        $award->__set('schedule', $this->pastVotingSchedule); // Cheated without hydrator
        $updated = Award::publishWinners($award, Carbon::now());
        $updated = Award::archive($updated, CurrentTime::set());
        $updated = Award::goLive($updated);

        $this->assertFalse($updated->isArchived());
        $this->assertTrue($updated->isLive());
    }


    /**
     * Tests archiving toggles off going live
     *
     * @return void
     */
    public function test_archive_toggles_off_go_live()
    {
        $award = Award::hydratable();
        $award->__set('schedule', $this->pastVotingSchedule); // Cheated without hydrator
        $updated = Award::goLive($award);
        $updated = Award::publishWinners($updated, Carbon::now());
        $updated = Award::archive($updated, CurrentTime::set());

        $this->assertTrue($updated->isArchived());
        $this->assertFalse($updated->isLive());
    }


    /**
     * Tests that unpublishing award removes archived and go-live flags
     *
     * @return void
     */
    public function test_unpublish_removes_archive_and_go_live()
    {
        $award = Award::hydratable();
        $award->__set('schedule', $this->pastVotingSchedule); // Cheated without hydrator
        $updated = Award::publishWinners($award, Carbon::now());
        $updated = Award::archive($updated, CurrentTime::set());
        $updated = Award::goLive($updated);
        $updated = Award::unpublish($updated);

        $this->assertFalse($updated->isArchived());
        $this->assertFalse($updated->isLive());
    }


    /**
     * Tests update the schedule
     *
     * @return void
     */
    public function test_update_schedule()
    {
        $updatedNominationStart = $this->nomStart->copy()->subDays(10);
        $updatedNominationEnd = $this->nomEnd->copy()->subDays(10);
        $updatedVotingStart = $this->votingStart->copy()->addDays(1);
        $updatedVotingEnd = $this->votingEnd->copy()->addDays(1);

        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($this->awardYear),
            new VotingSchedule(
                NominationDates::create($this->nomStart, $this->nomEnd),
                VotingDates::create($this->votingStart, $this->votingEnd)
            )
        );

        $updated = Award::updateSchedule(
            $award,
            new VotingSchedule(
                NominationDates::create($updatedNominationStart, $updatedNominationEnd),
                VotingDates::create($updatedVotingStart, $updatedVotingEnd)
            )
        );

        $this->assertEquals($updatedNominationStart, $updated->schedule()->nominations()->start());
        $this->assertEquals($updatedNominationEnd, $updated->schedule()->nominations()->end());
        $this->assertEquals($updatedVotingStart, $updated->schedule()->voting()->start());
        $this->assertEquals($updatedVotingEnd, $updated->schedule()->voting()->end());
    }


    /**
     * Tests updating the schedule fails when the voting end date
     * is after the winners announcement date
     *
     * @return void
     */
    public function test_update_schedule_fails_when_voting_end_is_after_winners_announced()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('You cannot move the voting end date after the winner announcement date');

        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($this->awardYear),
            new VotingSchedule(
                NominationDates::create($this->nomStart, $this->nomEnd),
                VotingDates::create($this->votingStart, $this->votingEnd)
            )
        );
        
        $updated = Award::publishWinners($award, $this->votingEnd->copy()->addDays(1));

        Award::updateSchedule(
            $updated,
            new VotingSchedule(
                NominationDates::create($this->nomStart, $this->nomEnd),
                VotingDates::create($this->votingStart, $this->votingEnd->copy()->addDays(2))
            )
        );
    }


    /**
     * Tests updating the schedule succeeds if the voting 
     * end date is before the winner announcement date
     *
     * @return void
     */
    public function test_update_schedule_succeeds_when_voting_end_is_before_winners_announced()
    {
        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($this->awardYear),
            new VotingSchedule(
                NominationDates::create($this->nomStart, $this->nomEnd),
                VotingDates::create($this->votingStart, $this->votingEnd)
            )
        );
        
        $updated = Award::publishWinners($award, $this->votingEnd->copy()->addDays(2));

        $updated = Award::updateSchedule(
            $updated,
            new VotingSchedule(
                NominationDates::create($this->nomStart, $this->nomEnd),
                VotingDates::create($this->votingStart, $this->votingEnd->copy()->addDays(1))
            )
        );
        
        $this->assertEquals($this->nomStart, $updated->schedule()->nominations()->start());
        $this->assertEquals($this->nomEnd, $updated->schedule()->nominations()->end());
        $this->assertEquals($this->votingStart, $updated->schedule()->voting()->start());
        $this->assertEquals($this->votingEnd->copy()->addDays(1), $updated->schedule()->voting()->end());
    }


    /**
     * Tests publishing winners
     *
     * @return void
     */
    public function test_publish_winners()
    {
        $this->expectException(DomainException::class);
        $award = Award::hydratable();
        $updated = Award::publishWinners($award, Carbon::now());
    }


    /**
     * Tests publishing winners successfully after voting ends
     *
     * @return void
     */
    public function test_publish_winners_after_voting_ends()
    {
        $now = Carbon::now();

        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($this->awardYear),
            new VotingSchedule(
                NominationDates::create($now->copy()->subDays(10), $now->copy()->subDays(9)),
                VotingDates::create($now->copy()->subDays(2), $now->copy()->subDays(1))
            )
        );
        $updated = Award::publishWinners($award, $now);
        $this->assertEquals($updated->winnersPublishedOn(), $now);
    }


    /**
     * Tests publishing winners is not allowed before voting ends
     *
     * @return void
     */
    public function test_publish_winners_not_allowed_before_voting_ends()
    {
        $now = Carbon::now();

        $this->expectException(DomainException::class);
        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($this->awardYear),
            new VotingSchedule(
                NominationDates::create($now->copy()->subDays(10), $now->copy()->subDays(9)),
                VotingDates::create($now->copy()->subDays(2), $now->copy()->addDays(1))
            )
        );
        $updated = Award::publishWinners($award, $now);
    }


    /**
     * Test unpublishing winners
     *
     * @return void
     */
    public function test_unpublish_winners()
    {
        $award = Award::start(
            new AwardTitle($this->awardTitle),
            new AwardYear($this->awardYear),
            new VotingSchedule(
                NominationDates::create(Carbon::now()->subDays(10), Carbon::now()->subDays(9)),
                VotingDates::create(Carbon::now()->subDays(2), Carbon::now()->subDays(1))
            )
        );
        $updated = Award::publishWinners($award, Carbon::now());
        $updated = Award::unpublishWinners($award);
        $this->assertEquals($updated->winnersPublishedOn(), null);
    }



}