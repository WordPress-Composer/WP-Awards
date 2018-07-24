<?php
namespace Voting\Controller;

use Voting\Model\Nominations;
use Voting\Model\Award;
use Voting\Exception\NominationException;
use Voting\Exception\EmailException;
use Voting\Helper\Server;
use Voting\Helper\Email;
use Config\Config;
use Btm\WordPressHelpers\Plugin;
use Btm\WordPressHelpers\Menu;
use Btm\WordPressHelpers\Request;
use Carbon\Carbon;

/**
 * @author VL
 *
 * A Nominations Controller for api endpoints
 */
class NominationsController extends BaseController
{
	use Plugin, Menu, Request, Server, Email;

	protected $config;

	public function __construct()
	{
		$this->config = Config::getInstance();
	}

	/**
	 * Saves a nomination and then sends emails to both user and ISA admin
	 */
	public function saveNomination()
	{
		try {
			Nominations::checkNominationValues($this->postObject());

			$award = Award::find($this->postObject()->batch);

			if (!$award) {
				throw new NominationException('Award does not exist');
			}

			$awardCategory = $award->categories()->where('category_id', '=', $this->postObject()->category_id)->first();

			if (!$awardCategory) {
				throw new NominationException('This category does not belong to this award. Nomination failed.');
			}

			$categoryId = $this->postObject()->category_id;
			$batch = $this->postObject()->batch;
			$userName = $this->postObject()->user_name;
			$userEmail = $this->postObject()->user_email;
			$nomineeName = $this->postObject()->nominee_name;
			$nominationReason = $this->postObject()->nomination_reason;
			$newsletter = strtolower($this->postObject()->newsletter) === 'true'? true: false;
			$ip = $this->serverGetVariables()->REMOTE_ADDR;

			Nominations::saveNomination(
				$categoryId,
				$batch,
				$userName,
				$userEmail,
				$nomineeName,
				$nominationReason,
				$newsletter,
				$ip
			);

		}
		catch (NominationException $e) {
			self::sendErrorMessage($e->getMessage(), 'INVALID_REQUEST');
		}
		catch (Exception $e) {
			self::sendErrorMessage('Failed to save nomination', 'SERVER_ERROR', 500);
		}

		try {
			if (strtolower(getenv("SEND_EMAIL")) === 'true') {
				$this->sendVoterEmails($categoryId, $userName, $userEmail, $nomineeName, $nominationReason);
				$this->sendISAEmails($categoryId, $userName, $userEmail, $nomineeName, $nominationReason, getenv("FROM_EMAIL"));
			}
		} 
		catch (EmailException $e) {
			wp_send_json('Nomination saved, however email was not sent', 200);
		}

		wp_send_json('Nomination saved', 200);
	}
}
