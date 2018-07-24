<?php 

namespace Voting\Domain;

use Carbon\Carbon;


/**
 * Generates phases based on the award year schedule
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Phase
{
    private $phase = '';

    private function __construct($phase)
    {
        $this->phase = $phase;
    }

    public static function confirm(
        Carbon $nominationStartDate,
        Carbon $nominationEndDate,
        Carbon $votingStartDate,
        Carbon $votingEndDate,
        Carbon $winnersAnnouncedOn = null,
        Carbon $currentDate = null
    ) {
        if (is_null($currentDate)) {
            $currentDate = Carbon::now();
        }

        if ($currentDate->gte($nominationStartDate) && $currentDate->lt($nominationEndDate)) {
            return new self('currentlyNominating');
        } else if ($currentDate->gte($nominationEndDate) && $currentDate->lt($votingStartDate)) {
            return new self('nominationClosed');
        } else if ($currentDate->gte($votingStartDate) && $currentDate->lt($votingEndDate)) {
            return new self('currentlyVoting');
        } else if ($currentDate->gte($votingEndDate) && $winnersAnnouncedOn instanceof Carbon && $currentDate->lt($winnersAnnouncedOn)) {
            return new self('votingClosed');
        } else if ($currentDate->gte($votingEndDate) && $winnersAnnouncedOn instanceof Carbon === false) {
            return new self('votingClosed');
        } else if ($winnersAnnouncedOn instanceof Carbon && $currentDate->gte($winnersAnnouncedOn)) {
            return new self('winnersAnnounced');
        }

        return new self('beforeNomination');
    }

    public function __toString()
    {
        return $this->phase;
    }
}