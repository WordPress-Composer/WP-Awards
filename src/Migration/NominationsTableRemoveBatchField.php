<?php 

namespace Voting\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;
use Illuminate\Database\Schema\Blueprint;


/**
 * Removes the batch field column
 * @author Gemma Black <gblackuk@gmail.com>
 */
class NominationsTableRemoveBatchField
{
    const TABLENAME = 'nominations';

    public static function databaseUp() {
        if (Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))
            && Capsule::schema()->hasColumn(Migrate::prefixedTableName(self::TABLENAME), 'batch')) {

            Capsule::schema()->table(Migrate::prefixedTableName(self::TABLENAME), function(Blueprint $table) {
                $table->dropColumn('batch');
            });
        }
    }
}

        