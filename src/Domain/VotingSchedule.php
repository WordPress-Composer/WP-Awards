<?php 

namespace Voting\Domain;

use Voting\Exception\DomainException;

/**
 * Voting schedule value object
 * @author Gemma Black <gblackuk@gmail.com>
 */
class VotingSchedule {

    /**
     * Scheduled nomination dates
     * @var DateRange
     */
    private $nominations;


    /**
     * Scheduled voting dates
     * @var DateRange
     */
    private $voting;


    /**
     * Creates voting schedule
     * @param DateRange $nominations
     * @param DateRange $voting
     */
    public function __construct(NominationDates $nominations, VotingDates $voting)
    {
        if ($voting->start()->lt($nominations->end())) {
            throw new DomainException('Voting start date must be on or after nominations end date');
        }

        $this->nominations = $nominations;
        $this->voting = $voting;
    }


    /**
     * Public interface to create voting schedule
     * @param DateRange $nominations
     * @param DateRange $voting
     * @return VotingSchedule
     */
    public static function create(DateRange $nominations, DateRange $voting)
    {
        return new self($nominations, $voting);
    }


    /**
     * Get nomination dates
     * @return DateRange
     */
    public function nominations()
    {
        return $this->nominations;
    }


    /**
     * Get voting dates
     * @return DateRange
     */
    public function voting()
    {
        return $this->voting;
    }
}