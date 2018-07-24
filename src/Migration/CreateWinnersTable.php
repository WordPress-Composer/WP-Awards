<?php 

namespace Voting\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;

class CreateWinnersTable
{

    const TABLENAME = 'winners';

    /**
     * Create the nominations table if it doesnt exist already
     */
    public static function databaseUp() {

        if (!Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))) {
            Capsule::schema()->create(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->increments('id');
                $table->integer('award_id')->unsigned();
                $table->integer('category_id')->unsigned();
                $table->integer('award_finalist_id')->unsigned();
                $table->integer('created_by')->nullable();
                $table->integer('last_updated_by')->nullable();
                $table->timestamps();
                $table->foreign('award_id')->references('id')->on('v_awards');
                $table->foreign('category_id')->references('id')->on('v_categories');
                $table->foreign('award_finalist_id')->references('id')->on('v_award_finalists');
                $table->unique(['award_id', 'category_id']);
            });
        }
    }

}