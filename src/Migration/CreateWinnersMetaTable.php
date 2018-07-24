<?php 

namespace Voting\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;

class CreateWinnersMetaTable
{

    const TABLENAME = 'winners_meta';

    /**
     * Create the nominations table if it doesnt exist already
     */
    public static function databaseUp() {

        if (!Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))) {
            Capsule::schema()->create(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->increments('id');
                $table->integer('award_finalist_id')->unsigned()->unique();
                $table->string('biography', 1000)->nullable();
                $table->string('video_url', 155)->nullable();
                $table->string('video_type', 30)->nullable();
                $table->integer('image_id')->nullable();
                $table->integer('created_by')->nullable();
                $table->integer('last_updated_by')->nullable();
                $table->timestamps();
                $table->foreign('award_finalist_id')->references('id')->on('v_award_finalists');
            });
        }
    }

}