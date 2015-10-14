<?php

namespace Facades;

use Skull\Routing\BaseRoute;

class Route
{
    public static function get($uri, $action)
    {
        $o = new BaseRoute;
        $o->get($uri, $action);
    }

    public static function post($uri, $action)
    {
        $o = new BaseRoute;
        $o->post($uri, $action);
    }

    public static function otherwise($action)
    {
        $o = new BaseRoute;
        $o->otherwise($action);
    }
}
