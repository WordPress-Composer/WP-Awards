<?php

use PHPUnit\Framework\TestCase;
use Voting\Domain\NominationDates;
use Voting\Domain\AwardTitle;
use Voting\Domain\AwardYear;
use Voting\Domain\VotingSchedule;
use Voting\Domain\Id;
use Voting\Domain\VotingDates;
use Carbon\Carbon;
use Voting\Hydrator\Award as AwardHydrator;

/**
 * Award domain tests
 * @author Gemma Black <gblackuk@gmail.com>
 */
class AwardHydratorTest extends TestCase
{

    protected $nomStart;
    protected $nomEnd;
    protected $votingStart;
    protected $votingEnd;
    protected $awardTitle = 'Some Test Award';
    protected $awardYear = '2018';

    public function setUp()
    {
        $this->nomStart = Carbon::now();
        $this->nomEnd = Carbon::now()->addHours(24);
        $this->votingStart = Carbon::now()->addMonth(1);
        $this->votingEnd = Carbon::now()->addMonth(1)->addHours(24);
    }

    public function test_should_hydrate_award_entity()
    {
        $now = Carbon::now();

        $hydrator = new AwardHydrator;
        $hydrator->setId(new Id(1));
        $hydrator->setTitle(new AwardTitle($this->awardTitle));
        $hydrator->setYear(new AwardYear($this->awardYear));
        $hydrator->setSchedule(new VotingSchedule(
            NominationDates::create($this->nomStart, $this->nomEnd),
            VotingDates::create($this->votingStart, $this->votingEnd)
        ));
        $hydrator->setWinnersPublishedOn($now);
        $award = $hydrator->hydrate();

        $this->assertEquals(1, $award->id()->value());
        $this->assertEquals($this->awardTitle, $award->title()->string());
        $this->assertEquals($this->awardYear, $award->year()->string());
        $this->assertEquals($this->nomStart, $award->schedule()->nominations()->start());
        $this->assertEquals($this->nomEnd, $award->schedule()->nominations()->end());
        $this->assertEquals($this->votingStart, $award->schedule()->voting()->start());
        $this->assertEquals($this->votingEnd, $award->schedule()->voting()->end());
        $this->assertEquals($now, $award->winnersPublishedOn());
    }

    public function test_should_be_partially_hydratable_with_only_title()
    {
        $hydrator = new AwardHydrator;
        $hydrator->setTitle(new AwardTitle($this->awardTitle));
        $award = $hydrator->hydrate();
        $this->assertEquals($this->awardTitle, $award->title()->string());
    }

}