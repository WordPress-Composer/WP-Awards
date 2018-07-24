<?php 

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use Voting\Domain\Phase;


/**
 * Testing the award phases
 * @author Gemma Black <gblackuk@gmail.com>
 */
class PhaseTest extends TestCase
{
    public function test_before_nominations()
    {
        $expected = 'beforeNomination';

        $currentDate = Carbon::now();
        $nominationStartDate = Carbon::now()->addDays(1);
        $nominationEndDate = Carbon::now()->addDays(2);
        $votingStartDate = Carbon::now()->addDays(3);
        $votingEndDate = Carbon::now()->addDays(4);
        $winnerAnnouncedOn = Carbon::now()->addDays(5);

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn,
            $currentDate
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_currently_nominating_phase()
    {
        $expected = 'currentlyNominating';

        $nominationStartDate = Carbon::now()->subDays(1);
        $currentDate = Carbon::now();
        $nominationEndDate = Carbon::now()->addDays(1);
        $votingStartDate = Carbon::now()->addDays(2);
        $votingEndDate = Carbon::now()->addDays(3);
        $winnerAnnouncedOn = Carbon::now()->addDays(4);

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn,
            $currentDate
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_nomination_closed()
    {
        $expected = 'nominationClosed';

        $nominationStartDate = Carbon::now()->subDays(2);
        $nominationEndDate = Carbon::now()->subDays(1);
        $currentDate = Carbon::now();
        $votingStartDate = Carbon::now()->addDays(1);
        $votingEndDate = Carbon::now()->addDays(2);
        $winnerAnnouncedOn = Carbon::now()->addDays(3);

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn,
            $currentDate
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_currently_voting()
    {
        $expected = 'currentlyVoting';

        $nominationStartDate = Carbon::now()->subDays(3);
        $nominationEndDate = Carbon::now()->subDays(2);
        $votingStartDate = Carbon::now()->subDays(1);
        $currentDate = Carbon::now();
        $votingEndDate = Carbon::now()->addDays(1);
        $winnerAnnouncedOn = Carbon::now()->addDays(2);

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn,
            $currentDate
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_voting_closed()
    {
        $expected = 'votingClosed';

        $nominationStartDate = Carbon::now()->subDays(4);
        $nominationEndDate = Carbon::now()->subDays(3);
        $votingStartDate = Carbon::now()->subDays(2);
        $votingEndDate = Carbon::now()->subDays(1);
        $currentDate = Carbon::now();
        $winnerAnnouncedOn = Carbon::now()->addDays(1);

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn,
            $currentDate
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_winners_announced()
    {
        $expected = 'winnersAnnounced';

        $nominationStartDate = Carbon::now()->subDays(5);
        $nominationEndDate = Carbon::now()->subDays(4);
        $votingStartDate = Carbon::now()->subDays(3);
        $votingEndDate = Carbon::now()->subDays(2);
        $winnerAnnouncedOn = Carbon::now()->subDays(1);
        $currentDate = Carbon::now();

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn,
            $currentDate
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_voting_closed_but_winners_have_not_been_announced_yet()
    {
        $expected = 'votingClosed';

        $nominationStartDate = Carbon::now()->subDays(4);
        $nominationEndDate = Carbon::now()->subDays(3);
        $votingStartDate = Carbon::now()->subDays(2);
        $votingEndDate = Carbon::now()->subDays(1);
        $currentDate = Carbon::now();
        $winnerAnnouncedOn = null;

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn,
            $currentDate
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_when_now_date_is_not_provided_it_uses_now_date_before_nominations()
    {
        $expected = 'beforeNomination';

        $nominationStartDate = Carbon::now()->addDays(1);
        $nominationEndDate = Carbon::now()->addDays(2);
        $votingStartDate = Carbon::now()->addDays(3);
        $votingEndDate = Carbon::now()->addDays(4);
        $winnerAnnouncedOn = null;

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_when_now_date_is_not_provided_it_uses_now_date_currently_nominating()
    {
        $expected = 'currentlyNominating';

        $nominationStartDate = Carbon::now()->subDays(1);
        $nominationEndDate = Carbon::now()->addDays(2);
        $votingStartDate = Carbon::now()->addDays(3);
        $votingEndDate = Carbon::now()->addDays(4);
        $winnerAnnouncedOn = null;

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_when_now_date_is_not_provided_it_uses_now_date_nomination_closed()
    {
        $expected = 'nominationClosed';

        $nominationStartDate = Carbon::now()->subDays(2);
        $nominationEndDate = Carbon::now()->subDays(1);
        $votingStartDate = Carbon::now()->addDays(3);
        $votingEndDate = Carbon::now()->addDays(4);
        $winnerAnnouncedOn = null;

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn
        );

        $this->assertEquals($expected, $phase);
    }

    public function test_when_now_date_is_not_provided_it_uses_now_date_currently_voting()
    {
        $expected = 'currentlyVoting';

        $nominationStartDate = Carbon::now()->subDays(3);
        $nominationEndDate = Carbon::now()->subDays(2);
        $votingStartDate = Carbon::now()->subDays(1);
        $votingEndDate = Carbon::now()->addDays(4);
        $winnerAnnouncedOn = null;

        $phase = Phase::confirm(
            $nominationStartDate, 
            $nominationEndDate,
            $votingStartDate,
            $votingEndDate,
            $winnerAnnouncedOn
        );

        $this->assertEquals($expected, $phase);
    }
}