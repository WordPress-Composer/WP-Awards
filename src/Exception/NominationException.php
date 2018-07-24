<?php
namespace Voting\Exception;

/**
 * @author VL
 *
 * Exception class for Nomination business loigc. Makes it more obvious what the
 * exception related to
 */

use Exception;

class NominationException extends Exception
{
	/**
	 * Construct class and extend PHP Exception class
	 */

	public function __construct($message, $code = 0, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
