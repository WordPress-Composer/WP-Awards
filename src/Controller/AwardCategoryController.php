<?php 

namespace Voting\Controller;

use Voting\Model\Category;
use Voting\Model\Award;
use Voting\Model\AwardFinalist;
use Voting\Transformer\CategoryTransformer;
use Voting\Transformer\Transform;

/**
 * Award category controller
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class AwardCategoryController extends BaseController
{

    public function create(array $params)
    {
        if (!$params['award_id']) {
            self::sendErrorMessage('Award id is not set');
        }

        if (!$params['category_id']) {
            self::sendErrorMessage('Category id is not set');
        }

        $award = Award::find($params['award_id']);

        if (!$award) {
            self::sendErrorMessage('Award does not exist', 'RESOURCE_NON_EXISTENT');
        }

        if (!Category::find($params['category_id'])) {
            self::sendErrorMessage('Category does not exist', 'RESOURCE_NON_EXISTENT');
        }

        // Already is a category
        $existing = $award->categories()->where('category_id', '=', $params['category_id'])->first();

        if ($existing) {
            self::sendErrorMessage($existing->name . ' has already been selected for award:' . $params['award_id'], 'LINK_EXISTS_ERROR');
        }

        $award->categories()->attach($params['category_id']);

        wp_send_json(Transform::collection($award->categories)->using(new CategoryTransformer), 200);
    }

    public function delete(array $params)
    {
        if (!$params['award_id']) {
            self::sendErrorMessage('Award id is not set');
        }

        if (!$params['category_id']) {
            self::sendErrorMessage('Category id is not set');
        }

        // Don't delete categories that already have finalists assigned
        $finalists = AwardFinalist::where('award_id', '=', $params['award_id'])
            ->where('category_id', '=', $params['category_id'])->get();

        if (count($finalists->toArray()) > 0) {
            self::sendErrorMessage(
                'This award category has finalists. You cannot delete it.',
                'INVALID_REQUEST'
            );
        }

        $award = Award::find($params['award_id']);

        if (!$award) {
            self::sendErrorMessage('Award does not exist');
        }

        if (!Category::find($params['category_id'])) {
            self::sendErrorMessage('Category does not exist', 'RESOURCE_NON_EXISTENT');
        }

        $award->categories()->detach($params['category_id']);

        return wp_send_json(null, 204);
    }

    public function all(array $params)
    {
        $award = Award::find($params['award_id']);

        if (!$award) {
            self::sendErrorMessage('Award does not exist');
        }

        $categories = $award->categories()
            ->with(['finalists' => function($query) use ($award) {
                $query->where('award_id', '=', $award->id);
            }, 'winner' => function($query) use ($award) {
                $query->where('award_id', '=', $award->id);
            }])->get();

        wp_send_json(Transform::collection($categories)->using(new CategoryTransformer), 200);
    }

}