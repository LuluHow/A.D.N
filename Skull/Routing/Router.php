<?php

namespace Skull\Routing;

use Skull\Http\Request;

class Router
{
    /**
     * Currently dispatched route.
     *
     * @var string
     */
    protected $route;

    /**
     * Array of parameters
     *
     * @var array
     */
    public $parameters = array();

    /**
     * The current URI.
     *
     * @var string
     */
    protected $uri;

    /**
     * Instance of Request class.
     *
     * @var Request
     */
    protected $request;
    
    /**
     * Is route matched.
     *
     * @var bool
     */
    public $isMatched;

    /**
     * Closure instance.
     *
     * @var Closure
     */
    protected $handler;

    /**
     * Create new Router instance.
     *
     * @param Request $request
     * @param string $route
     */
    public function __construct(Request $request, $route, $askMethod)
    {
        $this->isMatched = false;
        $this->request = $request;
        $this->route = $route;
        if($request->method === "GET" && $askMethod === "GET")
        {
            $this->get();
        }
        if($request->method === "POST" && $askMethod === "POST")
        {
            $this->post();    
        }
    }
    
    /**
     * Return an array of parameters.
     *
     * @param void
     * @return array
     */
    public function matches()
    {
        $path = $this->route;
        $pattern = '/({.*?})+/';
        $tmpPath = preg_replace($pattern, '([^/]+)', $path);
        $regexp = "#^$tmpPath$#i";
        preg_match_all($regexp, $this->request->uri, $matches);
        for($i = 1; $i < count($matches); $i++)
        {
            if(isset($matches[$i][0]))
            {
                array_push($this->parameters, $matches[$i][0]);
            }
        }
        if(isset($matches[0][0]))
        {
            return $matches[0][0];
        }
    }

    /**
     * Test get route.
     *
     * @param void
     * @return bool
     */
    public function get()
    {
        if($this->request->method == "GET")
        {
            $this->route = $this->matches();
            if($this->request->uri == $this->route)
            {
                $this->isMatched = true;
            }
            return false;
        }
    }

    /**
     * Test post route.
     *
     * @param void
     * @return bool
     */
    public function post()
    {
        if($this->request->method == "POST")
        {
            $this->route = $this->matches();
            if($this->request->uri == $this->route)
            {
                $this->isMatched = true;    
            }
            return false;
        }
    }
}
