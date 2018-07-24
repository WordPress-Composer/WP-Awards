<?php

namespace VotingMocks;

use Voting\Domain\CurrentTime;
use Carbon\Carbon;

/**
 * Solely for testing purposes. Allows for date times that are not 
 * system based.
 * 
 * @author Gemma Black <gblackuk@gmail.com>
 */
class MockCurrentTime extends CurrentTime
{
    public function setMock(Carbon $time) {
        return new self($time);
    }
}