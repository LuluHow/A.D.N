<?php

namespace Skull\Routing;

use Skull\Kernel\ADN;

class BaseRoute
{
    /**
     * Requested URI.
     *
     * @var string
     */
    protected $uri;

    /**
     * Handler.
     *
     * @var mixed
     */
    protected $action;

    /**
     * Create new Route instance.
     *
     * @param void
     * @return void
     */
    public function __construct() {}

    /**
     * New route on get method.
     *
     * @param string $uri
     * @param Closure $action
     * @return void
     */
    public function get($uri, $action)
    {
        ADN::dispatch($uri, $action, "GET");
    }

    /**
     * New route on post method.
     *
     * @param string $uri
     * @param Closure $action
     * @return void
     */
    public function post($uri, $action)
    {
        ADN::dispatch($uri, $action, "POST");
    }

    /**
     * Execute this function if no route matched.
     *
     * @param Closure $action
     * @return void
     */
    public function otherwise($action) {
        ADN::runCallable($action, array()); 
    }
}
