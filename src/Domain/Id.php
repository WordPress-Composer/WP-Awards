<?php

namespace Voting\Domain;

use Voting\Exception\DomainException;

class Id
{
    private $id;

    public function __construct($id = null)
    {
        if (!is_null($id) && !is_integer($id)) {
            throw new DomainException('Id should be an integer or null');
        }
        $this->id = $id;
    }

    public function value()
    {
        return (int) $this->id;
    }
}