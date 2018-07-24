<?php 

use PHPUnit\Framework\TestCase;
use Voting\Domain\AwardTitle;
use Voting\Exception\DomainException;

/**
 * Tests the creation of an award title with the domain rules
 * @author Gemma Black <gblackuk@gmail.com>
 */
class AwardTitleTest extends TestCase
{

    /**
     * Tests an award title can be created
     * @return void
     */
    public function testAwardTitleCanBeCreated()
    {
        $this->assertEquals(
            AwardTitle::create('My award title')->string(),
            'My award title'
        );
    }

    /**
     * Tests an award title cannot be created with a non-string
     * @return void
     */
    public function testAwardTitleCannotBeCreatedWithNonString()
    {
        $this->setExpectedException(DomainException::class);
        AwardTitle::create(1);
    }

    /**
     * Tests the award title must only be 80 characters
     * @return void
     */
    public function testAwardTitleCannotBeCreatedWithMoreThan80Characters()
    {
        $this->setExpectedException(DomainException::class);
        AwardTitle::create('Lorem ipsum dolor sit amet, consectetuer adipiscing elit. '
        . 'Aenean commodo ligula eget dolor. Aenean m...');
    }

    /**
     * Tests award title must not be an empty string
     * @return void
     */
    public function testAwardTitleMustNotBeAnEmptyString()
    {
        $this->setExpectedException(DomainException::class);
        AwardTitle::create('');
    }

}