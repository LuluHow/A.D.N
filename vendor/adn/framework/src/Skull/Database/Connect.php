<?php

namespace Skull\Database;

use PDO;
use Skull\Http\Path;

class Connect
{
    /**
     * Host for connection.
     *
     * @var string
     */
    private $host;

    /**
     * Database name.
     *
     * @var string
     */
    private $database;

    /**
     * Username.
     *
     * @var string
     */
    private $user;

    /**
     * Password.
     *
     * @var string
     */
    private $password;

    /**
     * PDO object.
     *
     * @var PDO
     */
    private $connection;
    
    /**
     * Return new instance of Connect.
     *
     * @param void
     * @return Connect $this
     */
    public function __construct()
    {
        $this->getVariables()->connect();
        return $this->getConnection();
    }

    /**
     * Set all variables for db connection.
     *
     * @param void
     * @return Connect $this
     */
    private function getVariables()
    {
        $realPath = new Path;
        include_once $realPath->path . "/config/database.php";
        $this->host = $database['host'];
        $this->database = $database['database'];
        $this->user = $database['user'];
        $this->password = $database['password'];
        return $this;
    }

    /**
     * Get connection to database.
     *
     * @param void
     * @return void
     */
    private function connect()
    {
        try
        {
            $this->connection = new PDO("mysql:host=".$this->host.";dbname=".$this->database, $this->user, $this->password);
        } 
        catch(Exception $e) 
        {
            echo "Error : " . $e->getMessage();
        }
    }

    /**
     * Return connection.
     *
     * @param void
     * @return PDO $connection
     */
    public function getConnection()
    {
        return $this->connection;    
    }
}
