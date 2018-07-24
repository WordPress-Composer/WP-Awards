<?php 

namespace Voting\Infrastructure;

/**
 * Router
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class Router
{

    /**
     * Namespace of API (to prevent clashing with other plugins)
     * @var string
     */
    private $namespace = 'voting';


    /**
     * Base API url
     * @example 'voting/v1'
     * @var string
     */
    private $base;


    /**
     * @param string $version
     */
    public function __construct($version)
    {
        $this->base = $this->namespace . '/' . $version;
    }


    /**
     * Get Http Request
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function get($route, callable $callback, $permissions = null)
    {
        new WPRouteHandler('GET', $this->base, $route, $callback, $permissions);
    }


    /**
     * Post Http Request
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function post($route, callable $callback, $permissions = null)
    {
        new WPRouteHandler('POST', $this->base, $route, $callback, $permissions);
    }


    /**
     * Path Http Request
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function patch($route, callable $callback, $permissions = null)
    {
        new WPRouteHandler('PATCH', $this->base, $route, $callback, $permissions);
    }


    /**
     * Delete Http Request
     * @param string $route
     * @param callable $callback
     * @return void
     */
    public function delete($route, callable $callback, $permissions = null)
    {
        new WPRouteHandler('DELETE', $this->base, $route, $callback, $permissions);
    }
}
