<?php 

namespace Voting\Infrastructure;

use WP_REST_Request;

/**
 * Wordpress route handling class
 * @author Gemma Black <gblackuk@gmail.com>
 */
final class WPRouteHandler
{

    private $method;
    private $base;
    private $route;
    private $callback;
    private $permissions = null;
    const NONCE_NAME = 'X-VOTING-NONCE';

    /**
     * WP Route Handler Constructor
     *
     * @param string $method POST,PUT,GET,DELETE etc
     * @param string $base Beginning of the route (namely for namespacing) /voting/v1
     * @param string $route The route
     * @param callable $callback
     * @param string $permissions edit_others_posts for editors, or activate_plugins for admins etc
     */
    public function __construct($method, $base, $route, callable $callback, $permissions = null)
    {
        $this->method = $method;
        $this->base = $base;
        $this->route = $route;
        $this->callback = $callback;
        $this->permissions = $permissions;
        add_action('rest_api_init', [$this, 'registerRoute']);
    }

    /**
     * Registers the route via Wordpress
     * @return void
     */
    public function registerRoute()
    {
        $method = $this->method;
        register_rest_route($this->base, $this->route, [
            'methods' => $this->method,
            'callback' => [$this, 'request'],
            'permission_callback' => [$this, 'permissionsCallback']
        ]);
    }


    /**
     * Passes the request to a route handlers
     * @param WP_REST_Request $request
     * @return void
     */
    public function request(WP_REST_Request $request)
    {
        new RouteHandler($this->callback, [$request->get_params(), $request->get_headers()]);
    }


    /**
     * Permissions callback
     *
     * @return void
     */
    public function permissionsCallback()
    {
        return is_null($this->permissions) || !is_null($this->permissions) && current_user_can($this->permissions);
    }
}