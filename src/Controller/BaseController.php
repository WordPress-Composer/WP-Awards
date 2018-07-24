<?php
namespace Voting\Controller;

use Btm\WordPressHelpers\Request;

/**
 * @author VL
 *
 * A base class which has helpers for api endpoints
 */
class BaseController
{
	use Request;

	/**
	 * Send generic http content
	 * @param  string  $content Content to be sent
	 * @param  integer $status  http status code
	 * @param  array   $headers Any http headers to attach to the response
	 */
	public static function send($content = '', $status = 200, $headers = array())
    {
		foreach ($headers as $value) {
			header($value);
		}

		http_response_code($status);
		echo $content;
		die();
    }

	/**
	 * Send json http content
	 * @param  string  $message Message content to be sent
	 * @param  integer $status  http status code
	 */
	public static function sendJson($content = '', $status = 200)
    {
		header('Content-Type: application/json');
		http_response_code($status);
		$data = [
			'message' => $content
		];
		echo json_encode($data);
		die();
    }


	/**
	 * Sends error message in json format
	 *
	 * @param string $message
	 * @param string $code default ERROR
	 * @param integer $status default 400
	 * @return void
	 */
	public static function sendErrorMessage($message, $code = 'ERROR', $status = 400)
	{
		wp_send_json([
            'error' => [
				'code' => $code,
				'message' => $message
			]
        ], $status);
	}


	/**
	 * Checks if relationship exists
	 *
	 * @param Object $object
	 * @param string $relationship
	 * @return bool
	 */
	protected function hasRelationship(array $params, $relationship)
	{
		return isset($params['relationships'][$relationship]);
	}


	/**
	 * Gets relationship
	 *
	 * @param Object $object
	 * @param string $relationship
	 * @return bool
	 */
	protected function getRelationship(array $params, $relationship)
	{
		return $params['relationships'][$relationship]['data'];
	}


	/**
	 * Checks if attribute based on json spec exists
	 *
	 * @param array $params
	 * @param string $key
	 * @return boolean
	 */
	protected function hasAttribute(array $params, $key)
	{
		return isset($params['data']['attributes'][$key]);
	}


	/**
	 * Checks if attributes based on json spec exists
	 *
	 * @param array $params
	 * @param array $keys
	 * @return boolean
	 */
	protected function hasAttributes(array $params, array $keys)
	{
		return count(array_filter($keys, function($key) use ($params) {
			return isset($params['data']['attributes'][$key]);
		})) === count($keys);
	}


	/**
	 * Gets attribute structured in the json spec way
	 *
	 * @param array $params
	 * @param string $key
	 * @param string $alt
	 * @return void
	 */
	protected function getAttribute(array $params, $key, $alt = null)
	{
		return $this->hasAttribute($params, $key) ? $params['data']['attributes'][$key] : $alt;
	}
}
