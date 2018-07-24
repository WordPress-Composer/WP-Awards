<?php 

namespace Voting\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;
use Illuminate\Database\Schema\Blueprint;

class NominationsTableBatchFieldType
{
    const TABLENAME = 'nominations';

    /**
     * Creates the award_id field type which replaces the batch_id
     * @author Gemma Black <gblackuk@gmail.com>
     */
    public static function databaseUp() {
        if (Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))
            && !Capsule::schema()->hasColumn(Migrate::prefixedTableName(self::TABLENAME), 'award_id')) {
            
            Capsule::schema()->table(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->unsignedInteger('award_id');
            });

            Capsule::schema()->table(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->foreign('award_id')->references('id')->on('v_awards');
            });
        }
    }

}