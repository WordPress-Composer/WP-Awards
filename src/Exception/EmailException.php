<?php

namespace Voting\Exception;

/**
 * @author Gemma Black <gblackuk@gmail.com>
 *
 * Exception class for parsing and sending Emails.
 */

use Exception;

class EmailException extends Exception
{
	/**
	 * Construct class and extend PHP Exception class
	 */

	public function __construct($message, $code = 0, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
