<?php 

namespace Voting\Domain;

interface StringValueObject 
{
    /**
     * Enforces the return of a value object string,
     * instead of relying on the magic method - which does not work 
     * with Eloquent
     * @return string
     */
    public function string();
}