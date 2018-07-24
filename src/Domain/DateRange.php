<?php 

namespace Voting\Domain;

use Carbon\Carbon;
use Voting\Exception\DomainException;

/**
 * Date range value object
 * @author Gemma Black <gblackuk@gmail.com>
 */
class DateRange
{

    /**
     * Date range start date
     * @var Carbon
     */
    protected $startDate;


    /**
     * Date range end date
     * @var Carbon
     */
    protected $endDate;


    /**
     * Privately constructs date range object
     * @param Carbon $startDate
     * @param Carbon $endDate
     */
    protected function __construct(Carbon $startDate, Carbon $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }


    /**
     * Create date range
     * @param Carbon $startDate
     * @param Carbon $endDate
     * @return DateRange
     */
    public static function create(Carbon $startDate, Carbon $endDate)
    {

        if ($endDate->lt($startDate)) {
            throw new DomainException('Start date must be after or on end date');
        }

        return new static($startDate, $endDate);
    }


    /**
     * Get date range start date
     * @return Carbon
     */
    public function start()
    {
        return $this->startDate;
    }


    /**
     * Get date range end date
     * @return Carbon
     */
    public function end()
    {
        return $this->endDate;
    }

}