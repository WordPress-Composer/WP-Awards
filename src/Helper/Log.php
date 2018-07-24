<?php

namespace Voting\Helper;

/**
 * Error logging classes
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Log
{
    /**
     * Logs an error
     *
     * @param any $error
     * @return void
     */
    public function error($error)
    {
        error_log($error);
    }
}
