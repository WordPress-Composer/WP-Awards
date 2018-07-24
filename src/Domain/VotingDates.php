<?php 

namespace Voting\Domain;

use Voting\Exception\DomainException;
use Carbon\Carbon;

class VotingDates extends DateRange
{
    protected function __construct($startDate, $endDate)
    {
        parent::__construct($startDate, $endDate);
        
    }

    public static function create(Carbon $startDate, Carbon $endDate)
    {
        $difference = $endDate->diffInMinutes($startDate);

        if ($endDate->lt($startDate)) {
            throw new DomainException('Voting start must be after or on voting end date');
        }

        if ($difference < 60) {
            throw new DomainException("Voting phase must be at least 1 hour");
        }

        return new self($startDate, $endDate);
    }
}