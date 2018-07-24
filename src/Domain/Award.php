<?php 

namespace Voting\Domain;

use Voting\Exception\DomainException;
use Carbon\Carbon;

final class Award extends Entity
{

    protected $id;

    protected $title;

    protected $year;

    protected $schedule;

    protected $isArchived = false;

    protected $isLive = false;

    protected $winnersAnnouncedOn = null;

    /**
     * Starts an award
     *
     * @param AwardTitle $title
     * @param AwardYear $year
     * @param VotingSchedule $schedule
     * @return Award
     */
    public static function start(AwardTitle $title, AwardYear $year, VotingSchedule $schedule)
    {
        $award = new self;
        $award->title = $title;
        $award->year = $year;
        $award->schedule = $schedule;
        return $award;
    }

    /**
     * Updates the title
     *
     * @param Award $award
     * @param AwardTitle $title
     * @return Award
     */
    public static function updateTitle(Award $award, AwardTitle $title)
    {
        $clone = clone $award;
        $clone->title = $title;
        return $clone;
    }

    /**
     * Updates the award year
     *
     * @param Award $award
     * @param AwardYear $year
     * @return Award
     */
    public static function updateYear(Award $award, AwardYear $year)
    {
        $clone = clone $award;
        $clone->year = $year;
        return $clone;
    }

    /**
     * Updates the schedule
     *
     * @param Award $award
     * @param VotingSchedule $schedule
     * @throws DomainException
     * @return Award
     */
    public static function updateSchedule(Award $award, VotingSchedule $schedule)
    {
        if (!is_null($award->winnersAnnouncedOn) 
            && $award->winnersAnnouncedOn->lt($schedule->voting()->end())) {
            
            throw new DomainException(
                'You cannot move the voting end date after the winner announcement date.'
                . ' Winners were published on ' . $award->winnersAnnouncedOn->format('Y-m-d \a\t H:i')
            );
        }

        $clone = clone $award;
        $clone->schedule = $schedule;
        return $clone;
    }

    /**
     * Puts the award live
     *
     * @param Award $award
     * @return Award
     */
    public static function goLive(Award $award)
    {
        $clone = clone $award;
        $clone->isArchived = false;
        $clone->isLive = true;
        return $clone;
    }

    /**
     * Archives the award
     *
     * @param Award $award
     * @return Award
     */
    public static function archive(Award $award, CurrentTime $currentTime)
    {
        if (is_null($award->winnersAnnouncedOn)) {
            throw new DomainException('Award cannot be archived before winners have been announced');
        }

        if (!is_null($award->year) && $award->year->string() > $currentTime->year()) {
            throw new DomainException('You cannot archive an award for a future year. We have not reached ' . $award->year->string() . ' yet');
        }

        $clone = clone $award;
        $clone->isLive = false;
        $clone->isArchived = true;
        return $clone;
    }

    /**
     * Unpublishes award
     *
     * @param Award $award
     * @return Award
     */
    public static function unpublish(Award $award)
    {
        $clone = clone $award;
        $clone->isLive = false;
        $clone->isArchived = false;
        return $clone;
    }

    /**
     * Publishes winners
     *
     * @param Award $award
     * @param Carbon $publishDate
     * @return Award
     */
    public static function publishWinners(Award $award, Carbon $publishDate)
    {
        $clone = clone $award;

        if (!isset($clone->schedule) || $clone->schedule->voting()->end()->gt($publishDate)) {
            throw new DomainException('Winners cannot be published before voting ends');
        }
        $clone->winnersAnnouncedOn = $publishDate->copy();
        return $clone;
    }

    /**
     * Unpublish winners
     *
     * @param Award $award
     * @return Award
     */
    public static function unpublishWinners(Award $award)
    {
        $clone = clone $award;
        $clone->winnersAnnouncedOn = null;
        return $clone;
    }

    /**
     * Gets title
     *
     * @return AwardTitle
     */
    public function title()
    {
        return $this->title;
    }

    /**
     * Gets the year
     *
     * @return AwardYear
     */
    public function year()
    {
        return $this->year;
    }

    /**
     * Gets the schedule
     *
     * @return VotingSchedule
     */
    public function schedule()
    {
        return $this->schedule;
    }

    /**
     * Gets the id
     *
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Checks the award is live
     *
     * @return boolean
     */
    public function isLive()
    {
        return $this->isLive;
    }

    /**
     * Checks the award is archived
     *
     * @return boolean
     */
    public function isArchived()
    {
        return $this->isArchived;
    }

    /**
     * Gets winners announcement date
     *
     * @return Carbon
     */
    public function winnersPublishedOn()
    {
        return $this->winnersAnnouncedOn;
    }
}