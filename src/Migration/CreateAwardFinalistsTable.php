<?php

namespace Voting\Migration;

/**
 * @author Gemma Black <gblackuk@gmail.com>
 *
 * Creates the finalists table
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;

class CreateAwardFinalistsTable
{
    const TABLENAME = 'award_finalists';

    /**
     * Create the award category pivot table if it doesnt exist already
     */
    public static function databaseUp() {

        if (!Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))) {
            Capsule::schema()->create(Migrate::prefixedTableName(self::TABLENAME), function($table) {

                $table->increments('id');
                $table->integer('award_id')->unsigned();
                $table->integer('category_id')->unsigned();
                $table->string('name', 80);
                $table->string('biography', 1000);
                $table->integer('image_id')->nullable();
                $table->integer('order_number');
                $table->timestamps();

                $table->foreign('award_id')
                    ->references('id')
                    ->on('v_awards');

                $table->foreign('category_id')
                    ->references('id')
                    ->on('v_categories');
            });
        }
    }
}
