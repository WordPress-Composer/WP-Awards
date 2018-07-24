<?php 

namespace Voting\Controller;

use Voting\Model\Vote;
use Voting\Model\AwardFinalist;
use Voting\Model\Award;
use Voting\Helper\Server;

/**
 * @author Gemma Black <gblackuk@gmail.com> 
 */
final class VotesController extends BaseController
{

    use Server;

    /**
     * @todo encapsulate each process into its own method eg. checking existing finalist etc
     */
    public function create(array $params)
    {

        if (empty($params['award_finalist_id'])
            || empty($params['user_name'])
            || empty($params['user_email'])
            || empty($params['newsletter'])) {
                self::sendErrorMessage('award_finalist_id, user_name, user_email and newsletter must be set', 'INVALID_PARAMETER_ERROR');
        }

        if (!filter_var($params['user_email'], FILTER_VALIDATE_EMAIL)) {
            self::sendErrorMessage('Email address format is invalid', 'INVALID_PARAMETER_ERROR');
        }

        // Check finalist exists
        $finalist = AwardFinalist::find($params['award_finalist_id']);
        if (!$finalist) {
            self::sendErrorMessage(
                'Finalist id: ' . $params['award_finalist_id'] . ' does not exist in database',
                'INVALID_RESOURCE_ERROR'
            );
        }

        $votesForAwardCategory = Vote::findUserVoteForCategory($finalist->category_id, $params['user_email'], $finalist->award_id);
        if ($votesForAwardCategory) {
            self::sendErrorMessage(
                'You have already voted for this award category',
                'EXISTING_ENTRY_ERROR'
            );
        }

        // Check ip limit has not been reached
        $ipRestricted = strtolower(getenv("VOTING_IP_RESTRICTION")) === 'true';
        $numberOfUsersPerIp = getenv("VOTING_USERS_PER_IP") ? intval(getenv("VOTING_USERS_PER_IP")) : 5;

        if ($ipRestricted) {
            $numberOfVotes = Vote::numberOfVotesByIpInAwardCategory(
                $finalist->category_id, 
                $finalist->award_id, 
                $this->serverGetVariables()->REMOTE_ADDR
            );

            if ($numberOfVotes >= $numberOfUsersPerIp) {
                self::sendErrorMessage('Maximum number of votes have been made for user ip');
            }
        }

        // Finally save vote
        $vote = Vote::saveNewVote(
            $finalist->award_id,
            $params['award_finalist_id'],
            $params['user_name'],
            $params['user_email'],
            $params['newsletter'],
            $this->serverGetVariables()->REMOTE_ADDR
        );

        wp_send_json([
            'type' => 'votes',
            'id' => $vote->id,
            'user_name' => $vote->user_name,
            'user_email' => $vote->user_email,
            'newsletter' => $vote->newsletter
        ], 201);
    }
}