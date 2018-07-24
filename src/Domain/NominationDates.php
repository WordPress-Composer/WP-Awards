<?php 

namespace Voting\Domain;

use Voting\Exception\DomainException;
use Carbon\Carbon;

/**
 * Nomination date value object
 * 
 * @author Gemma Black <gblackuk@gmail.com>
 */
class NominationDates extends DateRange
{
    protected function __construct(Carbon $startDate, Carbon $endDate)
    {
        parent::__construct($startDate, $endDate);
    }

    /**
     * Creates the nomination date range
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return NominationDates
     */
    public static function create(Carbon $startDate, Carbon $endDate)
    {
        $difference = $endDate->diffInMinutes($startDate);

        if ($endDate->lt($startDate)) {
            throw new DomainException('The "Nomination start" date must be before the "Nomination end" date');
        }

        if ($difference < 60) {
            throw new DomainException("Nominations must be at least 1 hour apart");
        }

        return new self($startDate, $endDate);
    }
}