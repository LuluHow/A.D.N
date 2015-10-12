<?php

namespace Skull\Database;

use Skull\Database\Connect;

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
