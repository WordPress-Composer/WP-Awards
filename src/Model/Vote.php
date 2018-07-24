<?php 

namespace Voting\Model;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * Constructor to create an instance of Winner
     */
    public function __construct(array $attributes = [])
    {
    	parent::__construct($attributes);
    	$this->table = 'v_votes';
    }

    /**
     * Creates a relationship with the finalist's table
     *
     * @return AwardFinalist
     */
    public function finalist()
    {
        return $this->belongsTo(AwardFinalist::class, 'award_finalist_id');
    }

    /**
     * Creates a relationship with the Award's table
     *
     * @return Award
     */
    public function award()
    {
        return $this->belongsTo(Award::class, 'award_id');
    }

    /**
     * Saves a new vote 
     *
     * @param int     $finalistId
     * @param string  $userName
     * @param string  $userEmail
     * @param boolean $readNewsletter
     * @param string  $userIp
     * @return void
     */
    public static function saveNewVote($awardId, $finalistId, $userName, $userEmail, $readNewsletter, $userIp)
    {
        $vote = new Vote;
        $vote->award_id = $awardId;
        $vote->award_finalist_id = $finalistId;
        $vote->user_name = $userName;
        $vote->user_email = $userEmail;
        $vote->newsletter = filter_var($readNewsletter, FILTER_VALIDATE_BOOLEAN);
        $vote->user_ip = $userIp;
        $vote->save();
        return $vote;
    }

    /**
     * Finds vote for an award category
     *
     * @param int    $categoryId
     * @param string $userEmail
     * @param int    $awardId
     * @return void
     */
    public static function findUserVoteForCategory($categoryId, $userEmail, $awardId)
    {
        return AwardFinalist::where('category_id', '=', $categoryId)
            ->where('award_id', '=', $awardId)
            ->whereHas('votes', function($query) use ($userEmail) {
                $query->where('user_email', '=', $userEmail);
            })
            ->first();
    }

    /**
     * Counts existing votes for award category
     *
     * @param int    $categoryId
     * @param int    $awardId
     * @param string $userIp
     * @return void
     */
    public static function numberOfVotesByIpInAwardCategory($categoryId, $awardId, $userIp)
    {
        $finalists = AwardFinalist::select('id')
            ->where('category_id', '=', $categoryId)
            ->where('award_id', '=', $awardId)
            ->get();

        $ids = $finalists->map(function($item) {
            return $item->id;
        });

        return Vote::whereIn('award_finalist_id', $ids)
            ->where('user_ip', '=', $userIp)
            ->count();
    }

}