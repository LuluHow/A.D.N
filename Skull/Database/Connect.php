<?php

namespace Skull\Database;

use PDO;

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
        $realPath = __DIR__;
        $file = file_get_contents($realPath."/../../.env");
        $env = explode("\n", $file);
        array_pop($env);

        for($i = 0; $i < count($env); $i++)
        {
            $env[$i] = explode("=", $env[$i]);
            switch($i)
            {
                case 0:
                    $this->host = $env[$i][1];
                    break;
                case 1:
                    $this->database = $env[$i][1];
                    break;
                case 2:
                    $this->user = $env[$i][1];
                    break;
                case 3:
                    $this->password = $env[$i][1];
                    break;
            }
        }
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
