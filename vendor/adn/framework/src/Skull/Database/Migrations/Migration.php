<?php

namespace Skull\Database\Migrations;

use Skull\Database\ConnectFactory;
use Skull\Database\Connect;
use Skull\Http\Path;

class Migration
{
    /**
     * PDO object contain current connection to database.
     *
     * @var PDO
     */
    protected $connect;

    /**
     * Table need to create or drop.
     *
     * @var string
     */
    protected $table;

    /**
     * Current request.
     *
     * @var string
     */
    protected $request;

    /**
     * Current column in traitment.
     *
     * @var strinf
     */
    protected $currColumn;

    /**
     * Create new instance of Migration.
     *
     * @param string
     * @return void
     */
    public function __construct($table)
    {
        $this->connect = ConnectFactory::getConnect();
        $this->table = $table;
        $this->request = "CREATE TABLE " . $this->table . "(id int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,";
    }

    /**
     * Add colum to migration.
     *
     * @param string
     * @param string
     * @param int
     * return $this;
     */
    protected function add($column, $type, $value=null)
    {
        if(!$value)
        {
            switch($type)
            {
                case "varchar":
                    $value = 255;
                    break;
                case 'int':
                    $value = 11;
                    break;
            }
        }
        $this->request .= " " . $column . " " . $type . "(" . $value . ") COLLATE utf8_unicode_ci,";
        return $this;
    }

    /**
     * Create new migration table.
     *
     * @param void
     * @return bool
     */
    protected function create()
    {
        $this->request = substr($this->request, 0, -1);
        $this->request .= ')';
        $query = $this->connect->getConnection()->prepare($this->request);
        if($query->execute())
        {
            return true;    
        }
        return false;
    }
}
