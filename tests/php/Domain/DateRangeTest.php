<?php 

use PHPUnit\Framework\TestCase;
use Carbon\Carbon;
use Voting\Domain\DateRange;
use Voting\Exception\DomainException;

/**
 * Tests date range creation
 * @author Gemma Black <gblackuk@gmail.com>
 */
class DateRangeTest extends TestCase
{


    /**
     * Tests creation of date range object from start and end dates
     */
    public function testCanCreateWithStartAndEndDates()
    {
        $startDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');
        $endDate = Carbon::createFromFormat('Y-m-j H:i', '2018-12-02 00:00');

        $this->assertEquals(
            DateRange::create($startDate, $endDate)->start(),
            $startDate
        );

        $this->assertEquals(
            DateRange::create($startDate, $endDate)->end(),
            $endDate
        );
    }


    /**
     * Tests that start date cannot be before end date
     * @return void
     */
    public function testEndDateCannotBeBeforeStartDate()
    {
        $startDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 09:00');
        $endDate = Carbon::createFromFormat('Y-m-j H:i', '2017-12-01 08:59');

        $this->setExpectedException(DomainException::class);

        DateRange::create($startDate, $endDate);
    }
}