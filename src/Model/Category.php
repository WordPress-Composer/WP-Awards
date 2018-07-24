<?php 

namespace Voting\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Category persistence model
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Category extends Model
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    /**
     * Constructor to create an instance of Category
     */
    public function __construct(array $attributes = [])
    {
    	parent::__construct($attributes);
    	$this->table = 'v_categories';
    }

    /**
     * Relates to finalists table
     *
     * @return Collection
     */
    public function finalists()
    {
        return $this->hasMany('Voting\Model\AwardFinalist');
    }

    public function winner()
    {
        return $this->hasOne(Winner::class);
    }

    /**
     * Relates to finalists table
     *
     * @return Collection
     */
    public function awards()
    {
        return $this->belongsToMany('Voting\Model\Award', 'v_award_category');
    }

}