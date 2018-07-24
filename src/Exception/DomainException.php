<?php

namespace Voting\Exception;

/**
 * @author Gemma Black <gblackuk@gmail.com>
 *
 * Exception class for Award business loigc. Makes it more obvious what the
 * exception related to
 */

use Exception;

class DomainException extends Exception
{
	/**
	 * Construct class and extend PHP Exception class
	 */

	public function __construct($message, $code = 0, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
