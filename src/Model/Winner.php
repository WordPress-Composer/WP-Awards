<?php 

namespace Voting\Model;

use Illuminate\Database\Eloquent\Model;

class Winner extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table;

    protected $fillable = [
        'award_id', 
        'award_finalist_id',
        'category_id',
        'created_by'
    ];

    /**
     * Constructor to create an instance of Winner
     */
    public function __construct(array $attributes = [])
    {
    	parent::__construct($attributes);
    	$this->table = 'v_winners';
    }

    public function award()
    {
        return $this->belongsTo(Award::class);
    }

    public function finalist()
    {
        return $this->belongsTo(AwardFinalist::class, 'award_finalist_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function meta()
    {
        return $this->hasOne(WinnerMeta::class, 'award_finalist_id', 'award_finalist_id');
    }
}