<?php

namespace Voting\Migration;

/**
 * Category creation table
 * @author Gemma Black <gblackuk@gmail.com>
 */

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;

class CreateCategoryTable
{
    const TABLENAME = 'categories';

    /**
     * Create the nominations table if it doesnt exist already
     */
    public static function databaseUp() {

        if (!Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))) {
            Capsule::schema()->create(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->increments('id');
                $table->string('name', 50)->unique();
                $table->string('description', 500);
                $table->string('short_label', 25);
                $table->string('slug', 60)->unique();
                $table->integer('created_by')->nullable();
                $table->integer('last_updated_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }
}
