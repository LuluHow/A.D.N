<?php

namespace Facades;

use Skull\Http\RootRedirection;

class Redirection
{
    public static function to($url)
    {
        $o = new RootRedirection;
        $o->to($url);
    }
}
