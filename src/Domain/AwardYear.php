<?php 

namespace Voting\Domain;

use Voting\Exception\DomainException;

/**
 * Award year value object
 * @author Gemma Black <gblackuk@gmail.com>
 */
class AwardYear implements StringValueObject
{

    /**
     * Award year
     * @var string
     */
    private $year;


    /**
     * Constructs the award year
     * @param string $year
     */
    public function __construct($year)
    {
        $int = (int) $year;
        $string = (string) $year;

        if (strlen($string) !== 4) {
            throw new DomainException('Award year must be 4 characters long');
        }

        $this->year = $int;
    }


    /**
     * Creates the award year
     * @param string $year
     * @return AwardYear
     */
    public static function create($year)
    {
        return new self($year);
    }


    /**
     * Returns award year as a string
     * @return string
     */
    public function string()
    {
        return $this->year;
    }
}