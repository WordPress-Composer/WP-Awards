<?php 

namespace Voting\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Winner's meta module
 * @author Gemma Black <gblackuk@gmail.com>
 */
class WinnerMeta extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table;

    /**
     * Constructor to create an instance of Winner
     */
    public function __construct(array $attributes = [])
    {
    	parent::__construct($attributes);
    	$this->table = 'v_winners_meta';
    }

    public function winner()
    {
        return $this->belongsTo(Winner::class, 'award_finalist_id', 'award_finalist_id');
    }

    public function finalist()
    {
        return $this->belongsTo(AwardFinalist::class, 'award_finalist_id');
    }
}