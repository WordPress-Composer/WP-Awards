<?php namespace Voting\Model;

use Config\Config;
use Voting\Exception\NominationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;
use Carbon\Carbon;


/**
 * A Nominations class that allows database acccess nominations info
 * @author VL
 */
class Nominations extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table;

    /**
     * Set if the table has a timestamp.
     */
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'id';

    /**
     * Constructor to create an instance of Nominations.
     */
    public function __construct()
    {
    	parent::__construct([]);

    	$this->table = 'v_nominations';
    }

    /**
     * Creates relationshpi with the Award's table
     *
     * @return Award
     */
    public function award()
    {
        return $this->belongsTo(Award::class, 'award_id');
    }

    /**
     * Relates to the Category's table
     *
     * @return Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

     /**
      * Saves Nominations with custom validation that depend on config
      * @param  string $categoryId       Category id
      * @param  string $batch            Nomination batch
      * @param  string $userName         Name of user
      * @param  string $userEmail        Email of user
      * @param  string $nomineeName      Nominee name
      * @param  string $nominationReason Nomination reason
      * @param  boolean $newsletter       Sign up ot newsletter
      * @param  string $ip               User IP
      */
    public static function saveNomination($categoryId, $batch, $userName, $userEmail, $nomineeName, $nominationReason, $newsletter, $ip)
    {
        self::checkNominationExist($categoryId, $batch, $userEmail, $nomineeName, $ip);
        self::checkIPNominationLimit($categoryId, $batch, $userEmail, $ip);

        $nominations = new Nominations();
        $nominations->category_id = $categoryId;
        $nominations->award_id = $batch;
        $nominations->user_name = $userName;
        $nominations->user_email = $userEmail;
        $nominations->nominee_name = $nomineeName;
        $nominations->nomination_reason = $nominationReason;
        $nominations->user_ip = $ip;
        $nominations->newsletter = filter_var($newsletter, FILTER_VALIDATE_BOOLEAN);
        $nominations->created_at = Carbon::now();

        $nominations->save();
    }

    /**
     * Validate that the Nomination does not exist, if it does throw NominationException
     * @param  string $categoryId  Category Id
     * @param  string $batch       Nomination batch
     * @param  string $userEmail   Email of user
     * @param  string $nomineeName Nominee name
     */
    private static function checkNominationExist($categoryId, $batch, $userEmail, $nomineeName)
    {
        $nominations = new Nominations();

        $count = Nominations::where('category_id', $categoryId)
                        ->where('award_id', $batch)
                        ->where('user_email', $userEmail)
                        ->count();

        if ($count > 0) {
            throw new NominationException('Your nomination already exists');
        }
    }

    /**
     * If voting ip restriction is turned on limit the nominations based on nummber of
     * users allowed to nominate per ip adddress
     * @param  string $categoryId Category id
     * @param  string $batch      Nomination batch
     * @param  string $userEmail  Email of user
     * @param  string $ip         User IP
     */
    private static function checkIPNominationLimit($categoryId, $batch, $userEmail, $ip)
    {
        $ipRestriction = strtolower(getenv("VOTING_IP_RESTRICTION")) === 'true'? true: false;

        if (!$ipRestriction) {
            return true;
        }

        $numberOfUsersPerIp = intval(getenv("VOTING_USERS_PER_IP"));

        $nominations = new Nominations();

        $userIPCount = Nominations::where('award_id', $batch)
                        ->where('user_ip', $ip)
                        ->where('category_id', $categoryId)
                        ->count();

        if ($userIPCount >= $numberOfUsersPerIp) {
            throw new NominationException('Your nomination already exist');
        }
    }

    /**
     * Basic check to see of nomination values is not null or empty from the post object
     * If it fails it throws a NominationException
     * @param  object $postObject Post object
     */
    public static function checkNominationValues($postObject)
    {
        if (self::IsNullOrEmptyString($postObject->category_id))
            throw new NominationException('Category id not set');

        if (self::IsNullOrEmptyString($postObject->batch))
            throw new NominationException('Batch not set');

        if (self::IsNullOrEmptyString($postObject->user_name))
            throw new NominationException('User name not set');

        if (self::IsNullOrEmptyString($postObject->nominee_name))
            throw new NominationException('Nominee name not set');

        if (self::IsNullOrEmptyString($postObject->nomination_reason))
            throw new NominationException('Nomination reason not set');
    }

    /**
     * Checks if value is null or empty
     */
    private static function IsNullOrEmptyString($value){
        return (!isset($value) || trim($value)==='');
    }
}
