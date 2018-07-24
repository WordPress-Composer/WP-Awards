<?php

namespace Voting\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;

class CreateVotesTable
{
    const TABLENAME = 'votes';

    /**
     * Create the nominations table if it doesnt exist already
     */
    public static function databaseUp() {

        if (!Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))) {
            Capsule::schema()->create(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                
                $table->increments('id');
                $table->integer('award_finalist_id')->unsigned();
                $table->string('user_name', 50);
                $table->string('user_email', 320);
                $table->string('user_ip', 32);
                $table->boolean('newsletter');
                $table->timestamps();

                $table->foreign('award_finalist_id')
                    ->references('id')
                    ->on(Migrate::prefixedTableName('award_finalists'));
            });
        }
    }
}
