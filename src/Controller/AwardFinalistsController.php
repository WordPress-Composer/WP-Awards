<?php 

namespace Voting\Controller;

use Voting\Model\AwardFinalist;
use Voting\Transformer\Transform;
use Voting\Transformer\AwardFinalistTransformer;

class AwardFinalistsController extends BaseController
{
    public function create(array $params)
    {
        if (empty($params['name'])) {
            self::sendErrorMessage('You cannot enter a finalist without a name');
        }

        if (empty($params['category_id'])) {
            self::sendErrorMessage('You cannot enter a finalist without a category_id');
        }

        if (empty($params['award_id'])) {
            self::sendErrorMessage('A finalist must be connected to an award by award_id');
        }

        if (!isset($params['biography'])) {
            self::sendErrorMessage('biography must be set');
        }

        if (empty($params['order_number'])) {
            self::sendErrorMessage('Order number must be set');
        }

        $existing = AwardFinalist::where('award_id', '=', $params['award_id'])
            ->where('category_id', '=', $params['category_id'])
            ->where('order_number', '=', $params['order_number'])->first();

        if ($existing) {
            self::sendErrorMessage('You cannot create more than one finalist for order number:' . $params['order_number'] . ' in this award category');
        }

        $finalist = new AwardFinalist;

        $finalist->award_id = $params['award_id'];
        $finalist->category_id = $params['category_id'];
        $finalist->name = $params['name'];
        $finalist->biography = $params['biography'];
        $finalist->image_id = isset($params['image_id']) ? $params['image_id'] : 0;
        $finalist->order_number = isset($params['order_number']) ? $params['order_number'] : 1;
        $finalist->save();

        wp_send_json(Transform::model($finalist)->using(new AwardFinalistTransformer), 200);
    }

    public function update(array $params)
    {
        if (empty($params['award_finalist_id'])) {
            self::sendErrorMessage('award_finalist_id must be set');
        }

        $finalist = AwardFinalist::find($params['award_finalist_id']);

        if (!$finalist) {
            self::sendError('Finalist does not exist');
        }

        $finalist->name = isset($params['name']) ? $params['name'] : $finalist->name;
        $finalist->biography = isset($params['biography']) ? $params['biography'] : $finalist->biography;
        $finalist->image_id = isset($params['image_id']) ? $params['image_id'] : $finalist->image_id;
        $finalist->order_number = isset($params['order_number']) ? $params['order_number'] : $finalist->order_number;
        $finalist->save();

        wp_send_json(Transform::model($finalist)->using(new AwardFinalistTransformer), 200);
    }

    public function delete(array $params)
    {
        if (!isset($params['id'])) {
            self::sendErrorMessage('id must be set');
        }

        // Cannot delete a finalist which is now a winner
        $finalistWithWinner = AwardFinalist::has('winner')->where('id', '=', $params['id'])->first();

        if ($finalistWithWinner) {
            self::sendErrorMessage('Cannot delete finalist that is now a winner', 'NOT_ALLOWED');
        }

        // Cannot delete a finalist that has votes
        $finalistWithVotes = AwardFinalist::has('votes')->where('id', '=', $params['id'])->first();

        if ($finalistWithVotes) {
            self::sendErrorMessage('You cannot delete this finalist as people have voted already', 'NOT_ALLOWED');
        }
        
        $finalist = AwardFinalist::find($params['id']);

        if (!$finalist) {
            self::sendErrorMessage('Cannot delete not existent finalist');
        }

        AwardFinalist::destroy($params['id']);
        wp_send_json(null, 204);
    }

    public function all(array $params)
    {
        $finalists = AwardFinalist::get();
        wp_send_json(Transform::collection($finalists)->using(new AwardFinalistTransformer), 200);
    }

    public function allByAward(array $params)
    {
        $finalists = AwardFinalist::where('award_id', '=', $params['id'])
            ->where('order_number', '<=', 3)->get();
        wp_send_json(Transform::collection($finalists)->using(new AwardFinalistTransformer), 200);
    }

    public function find(array $params)
    {
        $finalist = AwardFinalist::find($params['id']);
        if (!$finalist) {
            wp_send_json([ 'error' => ['message' => 'Resource does not exist']], 400);
        }
        wp_send_json(Transform::model($finalist)->using(new AwardFinalistTransformer), 200);
    }
}