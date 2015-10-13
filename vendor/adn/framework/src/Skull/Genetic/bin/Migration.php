<?php

use Skull\Database\Migrations\Migration;
use Skull\Database\ConnectFactory;

class TO REMPLACE extends Migration
{
    /**
     * Create new migration.
     */
    public static function migrate()
    {
        $TO REPLACE = new Migration('TO REPLACE');
        $TO REPLACE->add()
        //
        ->create();
    }

    /**
     * Reverse the migration.
     */
    public static function drop()
    {
        $TO REPLACE = new Migration();
        $TO REPLACE->drop();
    }
}
