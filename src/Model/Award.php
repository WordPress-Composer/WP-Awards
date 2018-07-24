<?php 

namespace Voting\Model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
/**
 * Awards persistence model
 * @author Gemma Black <gblackuk@gmail.com>
 */
class Award extends Model
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

    protected $fillable = [
        'title', 
        'year'
    ];
    
    /**
     * The attributes that should be mutated to Carbon dates.
     *
     * @var array
     */
    protected $dates = [
        'nomination_open',
        'nomination_close',
        'voting_open',
        'voting_close',
        'deleted_at',
        'winner_announcement_date'
    ];

    /**
     * Constructor to create an instance of Awards.
     */
    public function __construct(array $attributes = [])
    {
    	parent::__construct($attributes);
    	$this->table = 'v_awards';
    }

    /**
     * Relates to categories table
     *
     * @return Collection
     */
    public function categories()
    {
        return $this->belongsToMany('Voting\Model\Category', 'v_award_category')
            ->withTimestamps();
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

    /**
     * Relates to winners tables
     *
     * @return void
     */
    public function winners()
    {
        return $this->hasManyThrough(Winner::class, AwardFinalist::class);
    }

    /**
     * Relates to the submissions table
     *
     * @return void
     */
    public function submissions()
    {
        return $this->hasMany('Voting\Model\Nominations', 'award_id');
    }

    /**
     * Relates to votes table
     *
     * @return void
     */
    public function votes()
    {
        return $this->hasManyThrough(Vote::class, AwardFinalist::class);
    }

    public function votings()
    {
        return $this->hasMany('Voting\Model\Vote', 'award_id');
    }

    /**
     * Relates to the winners meta table
     *
     * @return void
     */
    public function winnersMeta()
    {
        return $this->hasManyThrough(WinnerMeta::class, AwardFinalist::class);
    }

}