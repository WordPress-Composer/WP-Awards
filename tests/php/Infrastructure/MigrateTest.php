<?php

use PHPUnit\Framework\TestCase;
use Voting\Infrastructure\Migrate;

/**
 * Testing migrate class
 * @author Gemma Black <gblackuk@gmail.com>
 */
class MigrateTest extends TestCase
{
    /**
     * Testing that route handler calls callback function by printing a sting
     * @return void
     */
    public function test_makes_prefixed_table_name()
    {
        $this->assertEquals('v_tableName', Migrate::prefixedTableName('tableName'));
    }
}