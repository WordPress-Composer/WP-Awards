<?php

namespace Voting\Migration;

/**
 * @author VL
 *
 * Create the Voting database tables
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;

class CreateNominationsTable
{
    const TABLENAME = 'nominations';

    /**
     * Create the nominations table if it doesnt exist already
     */
    public static function databaseUp() {

        if (!Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))) {
            Capsule::schema()->create(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->increments('id');
                $table->integer('category_id');
                $table->string('label', 30);
                $table->string('batch', 30);
                $table->string('user_name', 50);
                $table->string('user_email', 50);
                $table->string('user_ip', 32);
                $table->string('nominee_name', 50);
                $table->text('nomination_reason');
                $table->boolean('newsletter');
            });
        }
    }
}
