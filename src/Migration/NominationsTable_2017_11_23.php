<?php 

namespace Voting\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;

class NominationsTable_2017_11_23
{
    const TABLENAME = 'nominations';

    /**
     * Create the nominations table if it doesnt exist already
     */
    public static function databaseUp() {
        if (Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))
            && !Capsule::schema()->hasColumn(Migrate::prefixedTableName(self::TABLENAME), 'created_at')) {

            Capsule::schema()->table(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->dateTime('created_at');
            });
        }
    }

}