<?php 

namespace Voting\Infrastructure;

use PDOException;
use Exception;
use Error;
use Voting\Exception\DomainException;

/**
 * Route handling class
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class RouteHandler
{

    const SERVER_ERROR = 'SERVER_ERROR';
    const DOMAIN_LOGIC_EXCEPTION = 'DOMAIN_LOGIC_EXCEPTION';
    const PDO_ERROR = 'PDO_ERROR';

    public function __construct(callable $callback, array $params = [])
    {
        $this->handle($callback, $params);
    }


    /**
     * Handles callbacks responding with an API response if there is an error
     * @param callable $callback
     * @param array $params
     * @return void
     */
    private function handle(callable $callback, array $params)
    {
        try 
        {
            call_user_func_array($callback, $params);
        } 
        catch (PDOException $e) 
        {
            error_log($e);
            wp_send_json([
                'error' => [
                    'code' => self::PDO_ERROR,
                    'message' => 'There was an error the database. Check the logs.',
                ]
            ], 500);  
        }
        catch (DomainException $e)
        {
            error_log($e);
            wp_send_json([
                'error' => [
                    'code' => self::DOMAIN_LOGIC_EXCEPTION,
                    'message' => $e->getMessage()
                ]
            ], 400);  
        }
        catch (TypeError $e)
        {
            error_log($e);
            wp_send_json([
                'error' => [
                    'code' => self::SERVER_ERROR,
                    'message' => 'Type error'
                ]
            ], 500);  
        }
        catch (Error $e)
        {
            error_log($e);
            wp_send_json([
                'error' => [
                    'code' => self::SERVER_ERROR,
                    'message' => 'Error thrown'
                ]
            ], 500); 
        }
        catch (Exception $e) 
        {
            error_log($e);
            wp_send_json([
                'error' => [
                    'code' => self::SERVER_ERROR,
                    'message' => $e->getMessage()
                ]
            ], 500);  
        }
    }
}