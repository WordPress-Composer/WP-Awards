<?php

namespace Voting\Migration;

/**
 * @author Gemma Black <gblackuk@gmail.com>
 * Creates the awards table
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;

class CreateAwardsTable
{

    const TABLENAME = 'awards';

    /**
     * Create the awards table if it doesnt exist already
     */
    public static function databaseUp() {

        if (!Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))) {
            Capsule::schema()->create(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->increments('id');
                $table->string('title', 80);
                $table->string('year', 8);
                $table->dateTime('nomination_open');
                $table->dateTime('nomination_close');
                $table->dateTime('voting_open');
                $table->dateTime('voting_close');
                $table->dateTime('winner_announcement_date')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('last_updated_by')->nullable();
                $table->boolean('live')->default(false);
                $table->boolean('archived')->default(false);
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }
}
