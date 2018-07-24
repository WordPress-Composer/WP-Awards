<?php 

namespace Voting\Domain;

use Voting\Hydrator\Hydratable;

abstract class Entity implements Hydratable
{

    protected function __construct()
    {}

    /**
     * For hydration purposes ie. from persistence storage
     * DO NOT USE FOR BUSINESS LOGIC!
     *
     * @param string $key
     * @param any $value
     */
    public function __set($key, $value)
    {
        $this->{$key} = $value;
    }


    /**
     * Allows an entity to be hydrated/reconstituted, ie. from the database
     *
     * @return void
     */
    public static function hydratable()
    {
        return new static;
    }

}