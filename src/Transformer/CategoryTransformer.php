<?php 

namespace Voting\Transformer;

use Illuminate\Database\Eloquent\Model;
use Voting\Model\Category;

/**
 * Transforms the Eloquent Category model into an API friendly structure
 * following the JSON spec
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class CategoryTransformer extends Transformer
{

    /**
     * Builds model attributes structure. For custom structure, extend class
     *
     * @param Award $award
     * @return void
     */
    public function getItem(Model $category)
    {
        return self::build($category);
    }

    private static function build(Category $category)
    {
        return [
            'type' => 'categories',
            'id' => $category->id,
            'name' => stripslashes($category->name),
            'slug' => $category->slug,
            'description' => stripslashes($category->description),
            'shortLabel' => stripslashes($category->short_label),
            'finalists' => $category->finalists->count() === 0 
                ? [] : $category->finalists->map(function($finalist) {
                    return [
                        'id' => $finalist->id,
                        'name' => stripslashes($finalist->name),
                        'biography' => stripslashes($finalist->biography),
                        'imageId' => $finalist->image_id,
                        'imageUrl' => wp_get_attachment_url($finalist->image_id),
                        'orderNumber' => $finalist->order_number,
                        'awardId' => $finalist->award_id,
                        'categoryId' => $finalist->category_id
                    ];
                }),
            'winner' => !isset($category->winner) 
                ? null : Builder::buildWinner($category->winner)
        ];
    }

}