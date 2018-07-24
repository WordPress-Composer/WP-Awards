<?php

namespace Voting\Controller;

use Voting\Model\Award;

/**
 * Controller that access stats
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class StatsController extends BaseController
{

    /**
     * Gets award statistics
     *
     * @param array $params
     * @return void
     */
    public function getStatsByAwardId(array $params)
    {
        if (empty($params['id'])) {
            self::sendErrorMessage('award_id is missing', 'INVALID_PARAMETER_ERROR');
        }

        $award = Award::find($params['id']);

        if (!$award) {
            self::sendErrorMessage('Award with id:'. $params['id'] .' does not exist');
        }

        $statistics = [
            'id' => $award->id,
            'votes' => $award->votes()->count(),
            'submissions' => $award->submissions()->count()
        ];

        wp_send_json($statistics, 200);
    }

    /**
     * Gets the stats for all the awards
     *
     * @param array $params
     * @return void
     */
    public function getStats(array $params)
    {
        $awards = Award::withCount(['votings', 'submissions'])->get();

        return wp_send_json($awards->map(function(Award $award) {
          return [
              'id' => $award->id,
              'submissions' => $award->submissions_count,
              'votes' => $award->votings_count
          ];
        }), 200);
    }
}