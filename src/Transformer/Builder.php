<?php

namespace Voting\Transformer;

use Voting\Model\Winner;
use Voting\Model\WinnerMeta;
use Voting\Model\AwardFinalist;
use Voting\Model\Category;
use Voting\Helper\VideoService;
use Voting\Helper\Log;


/**
 * Transformer build helper
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Builder
{
    /**
     * Builds winner
     * @param Winner $winner
     * @return Object
     */
    public static function buildWinner(Winner $winner)
    {
        return [
            'id' => $winner->id,
            'awardId' => $winner->award_id,
            'finalistId' => $winner->award_finalist_id,
            'categoryId' => $winner->category_id,
            'name' => stripslashes($winner->finalist->name),
            'imageId' => self::getMeta($winner->finalist, 'image_id'),
            'imageUrl' => self::getMeta($winner->finalist, 'image_id') ? wp_get_attachment_url(self::getMeta($winner->finalist, 'image_id')) : null,
            'videoUrl' => self::getMeta($winner->finalist, 'video_url'),
            'videoType' => self::getVideoType(
                isset($winner->meta) && !empty($winner->meta->video_url) 
                    ? $winner->meta->video_url : ''
            ),
            'videoId' => self::getVideoId(
                isset($winner->meta) && !empty($winner->meta->video_url) 
                    ? $winner->meta->video_url : ''
            ),
            'biography' => stripslashes(self::getMeta($winner->finalist, 'biography'))
        ];
    }


    /**
     * Gets meta for winner model
     * @param Winner $winner
     * @param string $attribute
     * @return any
     */
    private static function getMeta(AwardFinalist $finalist, $attribute)
    {
        return !empty($finalist->meta) && !empty($finalist->meta[$attribute])
            ? $finalist->meta[$attribute] : $finalist[$attribute];
    }


    /**
     * Builds meta object
     * @param WinnerMeta $meta
     * @return Object
     */
    public static function buildMeta(WinnerMeta $meta)
    {
        return [
            'id' => $meta->id,
            'finalistId' => $meta->award_finalist_id,
            'awardId' => $meta->award_id,
            'biography' => stripslashes(self::getMeta($meta->finalist, 'biography')),
            'videoType' => self::getVideoType(
                !empty($meta->video_url) 
                    ? $meta->video_url : ''
            ),
            'videoId' => self::getVideoId(
                !empty($meta->video_url) 
                    ? $meta->video_url : ''
            ),
            'imageId' => self::getMeta($meta->finalist, 'image_id'),
            'imageUrl' => self::getMeta($meta->finalist, 'image_id') ? wp_get_attachment_url(self::getMeta($meta->finalist, 'image_id')) : null
        ];
    }




    /**
     * Builds finalist object
     * @param AwardFinalist $finalist
     * @return Object
     */
    public static function buildFinalist(AwardFinalist $finalist)
    {
        return [
            'id'            => $finalist->id,
            'name'          => stripslashes($finalist->name),
            'biography'     => stripslashes($finalist->biography),
            'imageId'       => (int) $finalist->image_id,
            'orderNumber'   => $finalist->order_number,
            'categoryId'    => $finalist->category_id,
            'imageUrl'      => $finalist->image_id ? wp_get_attachment_url($finalist->image_id) : null,
            'votes'         => current_user_can('edit_others_posts') ? $finalist->votes()->count() : 'AUTH_ACCESS_ONLY',
            'award_id'      => $finalist->award_id
        ];
    }


    /**
     * Builds category array
     * @param Category $category
     * @return Object
     */
    public static function buildCategory(Category $category)
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'description' => $category->description,
            'shortLabel' => $category->short_label,
            'slug' => $category->slug
        ];
    }

    private static function getVideoId($url)
    {
        $service = new VideoService(new Log, $url);
        return $service->getId();
    }

    private static function getVideoType($url)
    {
        $service = new VideoService(new Log, $url);
        return $service->getType();
    }
}