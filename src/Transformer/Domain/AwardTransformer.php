<?php 

namespace Voting\Transformer\Domain;

use Voting\Domain\Award;
use Voting\Domain\DateFormat;
use Voting\Domain\Phase;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Transforms the Domain awards object into an API friendly data structure 
 * @author Gemma Black <gblackuk@gmail.com>
 */
class AwardTransformer
{

    /**
     * Maps a single award to a response
     *
     * @param Award $award
     * @return object
     */
    public static function map(Award $award)
    {
        return self::body($award);
    }


    /**
     * Maps an array of award domain objects
     *
     * @param array $awards
     * @return object
     */
    public static function mapMany(array $awards)
    {
        return array_map(function($award) {
            return self::body($award);
        }, $awards);
    }


    /**
     * Builds award object attributes structure
     *
     * @param Award $award
     * @return void
     */
    private static function body(Award $award)
    {
        return [
            'type' => 'awards',
            'id' => $award->id()->value(),
            'title' => $award->title()->string(),
            'year' => $award->year()->string(),
            'nominationStartDate' => $award->schedule()->nominations()->start()->toDateTimeString(),
            'nominationEndDate' => $award->schedule()->nominations()->end()->toDateTimeString(),
            'votingStartDate' => $award->schedule()->voting()->start()->toDateTimeString(),
            'votingEndDate' => $award->schedule()->voting()->end()->toDateTimeString(),
            'isLive' => $award->isLive(),
            'isArchived' => $award->isArchived(),
            'winnersAnnouncedOn' => !is_null($award->winnersPublishedOn()) ?
                $award->winnersPublishedOn()->format(DateFormat::DEFAULT) : null,
            'phase' => (string) Phase::confirm(
                $award->schedule()->nominations()->start(),
                $award->schedule()->nominations()->end(),
                $award->schedule()->voting()->start(),
                $award->schedule()->voting()->end(),
                $award->winnersPublishedOn()
            )
        ];
    }

}