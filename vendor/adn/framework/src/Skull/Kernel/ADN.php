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
        $instanceOf = array();
        if(method_exists($object, $method))
        {
            $reflection = new \ReflectionMethod($object, $method);
            $z = $reflection->getParameters();
            foreach($z AS $k)
            {
                if($k->getClass()->name)
                {
                    $className = $k->getClass()->name;
                    if($className == "Skull\Http\\Request")
                    {
                        $o = static::$request;
                    } else {
                        $o = new $className();
                    }
                array_push($instanceOf, $o);
                }
            }
            $results = array_merge($instanceOf, $parameters);
            echo call_user_func_array(array($object, $method), $results);
            exit;
        }
    }
}
