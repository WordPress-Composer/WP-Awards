<?php 

namespace Voting\Domain;

use Carbon\Carbon;

/**
 * Current Time class that is extendable - which makes mocking
 * easier to do in tests.
 * @author Gemma Black <gblackuk@gmail.com>
 */
class CurrentTime
{
    protected $time;

    /**
     * @param Carbon $time
     */
    protected function __construct(Carbon $time)
    {
        $this->time = $time;
    }

    /**
     * Gets the date
     *
     * @return Carbon
     */
    public function date()
    {
        return $this->time;
    }

    /**
     * Sets the current time
     *
     * @return CurrentTime
     */
    public function set()
    {
        return new self(Carbon::now());
    }

    /**
     * Pretty carbon date as a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->time;
    }

    /**
     * Year
     *
     * @return int
     */
    public function year()
    {
        return $this->time->year;
    }
}