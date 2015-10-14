<?php

namespace Facades;

use Skull\View\RootView;

class View
{
    public static function make()
    {
        $args = func_get_args();
        $o = new RootView;
        return $o->make($args);
    }
}
