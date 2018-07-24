<?php 

namespace Voting\Domain;

use Voting\Exception\DomainException;

class Category
{
    public function __construct($id, $name)
    {
        if (!$name) {
            throw new DomainException('Category must have a name');
        }

        $this->id = $id;
        $this->name = $name;
    }
}