<?php 

namespace Voting\Controller;

use Voting\Model\Winner;
use Voting\Model\WinnerMeta;
use Voting\Model\AwardFinalist;
use Voting\Transformer\WinnerTransformer;
use Voting\Transformer\Transform;

/**
 * Winners coontroller
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class WinnersController extends BaseController
{

    /**
     * Creates or updates a winner for an award category
     * 
     * @param array $params
     * @return void
     */
    public function updateOrCreate(array $params)
    {
        if (!isset($params['biography'])
            || !isset($params['image_id'])
            || !isset($params['video_url'])
            || empty($params['category_id'])
            || empty($params['award_id'])
            || empty($params['finalist_id'])) {
                self::sendErrorMessage('biography, image_id, video_url, finalist_id, category_id and award_id should be provided', 'MISSING_PARAMETERS');
            }

        $finalist = AwardFinalist::find($params['finalist_id']);

        if (!$finalist) {
            self::sendErrorMessage('Cannot assign winner from a non-existent finalist');
        }

        $user = wp_get_current_user();

        $winner = Winner::updateOrCreate(
            [
                'award_id' => $params['award_id'], 
                'category_id' => $params['category_id']
            ],
            [
                'award_finalist_id' => $params['finalist_id'],
                'category_id' => $params['category_id'],
                'award_id' => $params['award_id']
            ]
        );

        $meta = isset($winner->meta) ? $winner->meta : new WinnerMeta;
        
        $meta->award_finalist_id = $params['finalist_id'];
        $meta->biography = $params['biography'];
        $meta->image_id = $params['image_id'];
        $meta->video_url = $params['video_url'];
        $meta->last_updated_by = $user->ID;

        $meta->save();

        wp_send_json(Transform::model($winner)->using(new WinnerTransformer), 200);
    }

    /**
     * Deletes a winner
     *
     * @param array $params
     * @return void
     */
    public function delete(array $params)
    {
        if (!isset($params['winner_id'])) {
            self::sendErrorMessage('winner_id is required', 'MISSING_PARAMETERS');
        }

        $winner = Winner::find($params['winner_id']);

        if (!$winner) {
            self::sendErrorMessage('Winner does not exist. You cannot update', 'NON_EXISTENT_RESOURCE');
        }

        $winner->delete();

        wp_send_json(null, 204);
    }

    public function allByAward(array $params)
    {
        $winners = Winner::where('award_id', '=', $params['award_id'])->get();
        wp_send_json(Transform::collection($winners)->using(new WinnerTransformer), 200);
    }
}