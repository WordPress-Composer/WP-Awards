<?php

namespace Voting\Migration;

/**
 * @author Gemma Black <gblackuk@gmail.com>
 *
 * Creates the award category pivot table
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;

class CreateAwardCategoryTable
{
    const TABLENAME = 'award_category';

    /**
     * Create the award category pivot table if it doesnt exist already
     */
    public static function databaseUp() {

        if (!Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))) {
            Capsule::schema()->create(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->integer('award_id')->unsigned();
                $table->integer('category_id')->unsigned();
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
