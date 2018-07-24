<?php 

namespace Voting\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AwardFinalist extends Model
{

    /**
     * The table associated with the model.
     */
    protected $table;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    protected $fillable = [
        'category_id', 
        'award_id',
        'order_number'
    ];

    /**
     * Constructor to create an instance of AwardFinalists.
     */
    public function __construct(array $attributes = [])
    {
    	parent::__construct($attributes);
    	$this->table = 'v_award_finalists';
    }

    public function winner()
    {
        return $this->hasOne(Winner::class, 'award_finalist_id');
    }

    public static function existingWinnersForAwardCategory($awardId, $categoryId)
    {
        return self::has('winner')->where('category_id', '=', $categoryId)
            ->where('award_id', '=', $awardId)
            ->get()->toArray();
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function award()
    {
        return $this->hasOne(Award::class);
    }

    public function meta()
    {
        return $this->hasOne(WinnerMeta::class);
    }
}