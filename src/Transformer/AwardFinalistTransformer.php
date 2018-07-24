<?php 

namespace Voting\Transformer;

use Voting\Model\AwardFinalist;
use Illuminate\Database\Eloquent\Model;

final class AwardFinalistTransformer extends Transformer
{

    public function getItem(Model $model)
    {
        return $this->build($model);
    }

    private function build(AwardFinalist $finalist)
    {
        return [
            'id'            => $finalist->id,
            'name'          => stripslashes($finalist->name),
            'biography'     => stripslashes($finalist->biography),
            'imageId'       => (int) $finalist->image_id,
            'orderNumber'   => (int) $finalist->order_number,
            'categoryId'    => (int) $finalist->category_id,
            'imageUrl'      => $finalist->image_id ? wp_get_attachment_url($finalist->image_id) : false,
            'votes'         => current_user_can('edit_others_posts') ? $finalist->votes()->count() : 'AUTH_ACCESS_ONLY',
            'award_id'      => $finalist->award_id
        ];
    }
}