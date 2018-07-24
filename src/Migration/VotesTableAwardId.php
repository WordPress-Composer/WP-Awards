<?php 

namespace Voting\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Voting\Infrastructure\Migrate;
use Voting\Model\Vote;
use Voting\Model\AwardFinalist;

/**
 * Votes table update to include an award id because it makes
 * querying data more easier and hence performant. Otherwise we'd have
 * to use several joins.
 * @author Gemma Black <gblackuk@gmail.com>
 */
class VotesTableAwardId
{
    const TABLENAME = 'votes';

    /**
     * Create the nominations table if it doesnt exist already
     */
    public static function databaseUp() {
        if (Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))
            && !Capsule::schema()->hasColumn(Migrate::prefixedTableName(self::TABLENAME), 'award_id')) {

            Capsule::schema()->table(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->unsignedInteger('award_id')->nullable();
            });

            Capsule::schema()->table(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $table->foreign('award_id')->references('id')->on('v_awards');
            });
        }

        if (Capsule::schema()->hasTable(Migrate::prefixedTableName(self::TABLENAME))
            && Capsule::schema()->hasColumn(Migrate::prefixedTableName(self::TABLENAME), 'award_id')) {

            Capsule::schema()->table(Migrate::prefixedTableName(self::TABLENAME), function($table) {
                $votes = Vote::all();

                foreach ($votes as $vote) {
                    self::populateAwardsIdColumn($vote);
                }
            });
        }
    }

    private static function populateAwardsIdColumn(Vote $vote) {
        if (is_null($vote->award_id)) {
            $finalistId = $vote->award_finalist_id;
            $finalist = AwardFinalist::find($finalistId);
            $vote->award_id = $finalist->award_id;
            $vote->save();
        }
    }

}