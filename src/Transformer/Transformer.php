<?php

namespace Voting\Transformer;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Transformer
{

    private $with;

    public function __construct(array $with = [])
    {
        $this->with = $with;
    }

    public static function with(array $with)
    {
        return new static($with);
    }

    /**
     * Creates generic json structure. For specific implementation, 
     * extend model.
     * 
     * @param Model $model
     * @param array $with
     * @return void
     */
    public function getItem(Model $model)
    {
        return [
            'id' => $model->id,
            'attributes' => $model->getAttributes()
        ];
    }

    public function getItems(Collection $collection)
    {
        return $collection->map(function($model) {
            return $this->getItem($model);
        });
    }
}