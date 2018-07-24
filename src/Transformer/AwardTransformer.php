<?php 

namespace Voting\Transformer;

use Voting\Model\Award;
use Voting\Domain\DateFormat;
use Voting\Domain\Phase;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Award Transformer of the Award Eloquent model
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class AwardTransformer extends Transformer
{

    public function getItem(Model $model)
    {
        return self::build($model);
    }


    /**
     * (JSON) Object builder
     *
     * @param Award $award
     * @return Object
     */
    private static function build(Award $award)
    {
        return [
            'type' => 'awards',
            'id' => $award->id,
            'title' => $award->title,
            'year' => $award->year,
            'nominationStartDate' => $award->nomination_open->format(DateFormat::DEFAULT),
            'nominationEndDate' => $award->nomination_close->format(DateFormat::DEFAULT),
            'votingStartDate' => $award->voting_open->format(DateFormat::DEFAULT),
            'votingEndDate' => $award->voting_close->format(DateFormat::DEFAULT),
            'isLive' => $award->live,
            'isArchived' => $award->archived,
            'winnersAnnouncedOn' => $award->winner_announcement_date ? $award->winner_announcement_date->format(DateFormat::DEFAULT) : null,
            'phase' => (string) Phase::confirm(
                $award->nomination_open,
                $award->nomination_close,
                $award->voting_open,
                $award->voting_close,
                $award->winner_announcement_date
            ),
            'categories' => $award->categories->map(function($category) {
                return Builder::buildCategory($category);
            }),
            'finalists' => $award->finalists->map(function($finalist) {
                return Builder::buildFinalist($finalist);
            }),
            'winners' => $award->winners->map(function($winner) {
                return Builder::buildWinner($winner);
            }),
            'winnersMeta' => $award->winnersMeta->map(function($meta) {
                return Builder::buildMeta($meta);
            })
        ];
    }
}