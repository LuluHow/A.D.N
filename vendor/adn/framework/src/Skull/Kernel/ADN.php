<?php

namespace Skull\Kernel;

use Closure;
use Skull\Http\Request;
use Skull\Routing\Router;

class ADN
{
    /**
     * Current request
     *
     * @var Request
     */
    public static $request;
    
    /**
     * Callback.
     *
     * @var Closure
     */
    protected $handler;

    /**
     * Run callback.
     *
     * @param Closure $action
     * @param array $parameters
     * @return void
     */
    public static function runCallable(Closure $action, $parameters)
    {
        echo call_user_func_array($action, $parameters);
    }

    /**
     * Test route and call the closure.
     *
     * @param string $route
     * @param mixed $action
     * @return void
     */
    public static function dispatch($route, $action, $askMethod)
    {
        static::$request = new Request($_SERVER);
        $match = new Router(static::$request, $route, $askMethod);
        if($match->isMatched) 
        {
            if(gettype($action) === "string")
            {
                $actionArray = explode("#", $action);
                static::runController($actionArray[0], $actionArray[1], $match->parameters);
            } else {
                static::runCallable($action, $match->parameters);
                exit;
            }
        }
    }

    /**
     * Run controller and method.
     *
     * @param string $controller
     * @param string $method
     * @return void
     */
    private static function runController($controller, $method, $parameters=null)
    {
        include_once(__DIR__ . "/../../../../../../app/Controllers/" . $controller . '.php');
        $class = 'App\\Controllers\\' . $controller;
        $object = new $class();
        if(method_exists($object, $method))
        {
            $reflection = new \ReflectionMethod($object, $method);
            $z = $reflection->getParameters();
            foreach($z AS $k)
            {
                if($k->getClass()->name === "Skull\\Http\\Request")
                {
                    array_unshift($parameters, static::$request);
                }
            }

            echo call_user_func_array(array($object, $method), $parameters);
            exit;
        }
    }
}
