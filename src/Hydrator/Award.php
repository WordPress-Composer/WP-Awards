<?php

namespace Voting\Hydrator;

use Voting\Domain\AwardTitle;
use Voting\Domain\AwardYear;
use Voting\Domain\VotingSchedule;
use Voting\Domain\Award as AwardDomain;
use Carbon\Carbon;

/**
 * Award entity hydrator to reconstitute the entity without breaking the invariants
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Award
{
    private $award;

    public function __construct()
    {
        $this->award = AwardDomain::hydratable();
    }

    public function setId($id)
    {
        $this->award->__set('id', $id);
    }

    public function setTitle(AwardTitle $title)
    {
        $this->award->__set('title', $title);
    }

    public function setYear(AwardYear $year)
    {
        $this->award->__set('year', $year);
    }

    public function setSchedule(VotingSchedule $schedule)
    {
        $this->award->__set('schedule', $schedule);
    }

    public function setIsLive($isLive)
    {
        $this->award->__set('isLive', $isLive);
    }

    public function setIsArchived($isArchived)
    {
        $this->award->__set('isArchived', $isArchived);
    }

    public function setWinnersPublishedOn(Carbon $date = null)
    {
        $this->award->__set('winnersAnnouncedOn', $date);
    }

    public function hydrate()
    {
        return $this->award;
    }
}