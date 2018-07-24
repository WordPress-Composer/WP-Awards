<?php 

namespace Voting\Domain;

use Voting\Exception\DomainException;


/**
 * Award title value object
 * @author Gemma Black <gblackuk@gmail.com>
 */
class AwardTitle implements StringValueObject
{
    
    /**
     * Award title
     * @var string
     */
    private $title;


    /**
     * Constructs award title
     * @param string $title
     */
    public function __construct($title)
    {
        if (!is_string($title)) {
            throw new DomainException('Title must be a string');
        }

        if (strlen($title) > 80) {
            throw new DomainException('Title must be 80 characters or less');
        }

        if (empty($title)) {
            throw new DomainException('Title must have at least 1 character');
        }

        $this->title = $title;
    }


    /**
     * Creates a new award title
     * @param string $title
     * @return AwardTitle
     */
    public static function create($title)
    {
        return new self($title);
    }

    /**
     * Returns award title a string 
     * @return string
     */
    public function string()
    {
        return $this->title;
    }
}