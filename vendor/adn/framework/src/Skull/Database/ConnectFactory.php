<?php

namespace Skull\Database;

use Skull\Database\Connect;
use Skull\Http\Path;

class ConnectFactory
{
    /**
     * Return new instance of Connect.
     *
     * @param void
     * @return Connect
     */
    public static function getConnect()
    {
        return new Connect; 
    }
}
