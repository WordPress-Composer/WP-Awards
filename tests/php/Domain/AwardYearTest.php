<?php 

use PHPUnit\Framework\TestCase;
use Voting\Domain\AwardYear;
use Voting\Exception\DomainException;

/**
 * Tests the creation of an award year with the domain rules
 * @author Gemma Black <gblackuk@gmail.com>
 */
class AwardYearTest extends TestCase
{

    /**
     * Tests an award title can be created
     * @return void
     */
    public function testAwardYearCanBeCreated()
    {
        $this->assertEquals(
            AwardYear::create('2017')->string(),
            '2017'
        );
    }


    /**
     * Tests only strings are accepted
     * @return void
     */
    public function testAwardOnlyAcceptsString()
    {
        $this->setExpectedException(DomainException::class);
        AwardYear::create(123)->string();

        $this->setExpectedException(DomainException::class);
        AwardYear::create(true)->string();

        $this->setExpectedException(DomainException::class);
        AwardYear::create([])->string();
    }


    /**
     * Tests a minimum of 4 characters are allowed
     * @return void
     */
    public function testThatLessThanFourCharactersThrowsException()
    {
        $this->setExpectedException(DomainException::class);
        AwardYear::create('201')->string();
    }


    /**
     * Tests a maximum of r characters are allowed
     * @return void
     */
    public function test_That_More_Than_Five_Characters_Throws_Exception()
    {
        $this->setExpectedException(DomainException::class);
        AwardYear::create('12345')->string();
    }


    /**
     * Tests 10 characters or less is allowed
     * @return void
     */
    public function test_Four_Characters_Is_Acceptable()
    {
        $this->assertEquals(
            AwardYear::create('2019')->string(),
            '2019'
        );
    }

}