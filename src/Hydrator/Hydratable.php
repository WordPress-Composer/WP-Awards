<?php 

namespace Voting\Hydrator;

interface Hydratable {

    /**
     * Returns entity or model called from
     *
     * @return Entity
     */
    public static function hydratable();
}