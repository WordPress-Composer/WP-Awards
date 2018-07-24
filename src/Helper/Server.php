<?php
namespace Voting\Helper;

/**
 * @author RB
 *
 * Provides a simple interface for interacting with PHP server variables
 */

trait Server
{
	public function serverGetVariables()
	{
		return (object) $_SERVER;
	}
}
