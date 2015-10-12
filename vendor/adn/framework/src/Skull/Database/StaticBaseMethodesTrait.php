<?php

namespace Skull\Database;

use Skull\Database\ConnectFactory;
use PDO;

trait StaticBaseMethodesTrait
{
     /**
     * Object contain connection.
     *
     * @var PDO 
     */
    public static $connect;

    /**
     * Contain current request.
     *
     * @var PDO 
     */
    public static $currentQuery;

    /**
     * Contain the request.
     *
     * @var string
     */
    public static $request;

    /**
     * Contain the results.
     *
     * @var stdClass
     */
    public static $data;

    /**
     * Contain ID of last inserted row.
     *
     * @var int
     */
    public static $lastInsert;

    /**
     * Contain join clause(s)
     *
     * @var string
     */
    public static $join;

    /**
     * Contain current table for use.
     *
     * @var string
     */
    public static $table;

    /**
     * WHERE clause.
     *
     * @var string
     */
    public static $clause;

    /**
     * Condition for WHERE clause.
     *
     * @var string
     */
    public static $parameters;

    /**
     * SELECT clause.
     *
     * @var string
     */
    public static $select;

    /**
     * Initialize model.
     *
     * @param string $table
     * @return void
     */
    public static function construct($table) 
    {
        static::$connect = ConnectFactory::getConnect();
        static::$table = $table;
        static::$select = null;
        static::$clause = "";
        static::$join = "";
    }
    
    /**
     * Construct WHERE clause.
     *
     * @param string $clause
     * @param string $operande
     * @param string $parameter
     * @return $this
     */
    public static function where($clause, $operande, $parameter)
    {
        static::$clause = " WHERE " . $clause . " " . $operande . " ?";
        static::$parameters = $parameter;
        return $this;
    }

    /**
     * Construct request.
     *
     * @param int $limit
     * @return void
     */
    public static function constructSelectRequest($limit = false)
    {
        if($limit)
        {
            $limit = " LIMIT " . $limit;    
        }
        if(static::$select == null && static::$request == null)
        {
            static::$request = "SELECT * FROM " . static::$table . static::$join . static::$clause . $limit;
        }
        else
        {
            static::$request = static::$select . static::$table . static::$join . static::$clause . $limit;    
        }
    }

    /**
     * Prepare request.
     *
     * @param void
     * @return PDO $query
     */
    public static function prepareRequest()
    {
        try
        {
            static::$currentQuery = static::$connect->getConnection()->prepare(static::$request);
            return static::$currentQuery;
        }
        catch(Exception $e)
        {
            echo "Error : " . $e->getMessage();   
        }
    }

    /**
     * Execute request.
     *
     * @param void 
     * @return PDO $query
     */
    public static function executeRequest()
    {
        try 
        {
            if(is_array(static::$parameters))
            {
                static::$currentQuery->execute(static::$parameters);
            }
            else
            {
                static::$currentQuery->execute(array(static::$parameters));
            }
            return static::$currentQuery;
        }
        catch(Exception $e)
        {
            echo "Error : " . $e->getMessage();   
        }
    }

    /**
     * Access to data using request.
     *
     * @param int $limit
     * @return stdClass $data
     */
    public static function get($limit = false)
    {
        if(static::$request == null)
        {
            static::constructSelectRequest($limit);
        }
        static::prepareRequest();
        try
        {
            static::$data = static::executeRequest()->fetchAll(PDO::FETCH_OBJ);
            if(count(static::$data) < 2)
            {
                static::$data = static::$data[0];    
            }
            return static::$data;
        }
        catch(Exception $e)
        {
            echo "Erreur : " . $e->getMessage();   
        }
    }

    /**
     * Construct insert request.
     *
     * @param array $array
     * @return array $insertRows
     */
    private static function constructInsertRequest(array $array)
    {
        $fields = "(";
        $values = "(";
        $insertRows = array();
        $i = 0;
        foreach($array AS $key => $value)
        {
            if($i == (count($array))-1)
            {
                $fields .= $key . ")";
                $values .= ":" . $key . ")";
            }
            else
            {
                $fields .= $key . ", ";
                $values .= ":" . $key . ",";
            }
            $insertRows[":" . $key] = $value;
            $i++;
        }
        static::$request = "INSERT INTO " . static::$table . $fields . " VALUES" . $values;
        static::$parameters = $insertRows;
    }

    /**
     * Perform insert query.
     *
     * @param array $array 
     * @return Base $this
     */ 
    public static function insert(array $array)
    {
        $insertRows = static::constructInsertRequest($array);
        try
        {
            static::prepareRequest();
            static::executeRequest();
            static::$lastInsert = static::$connect->getConnection()->lastInsertId();
            return $this;
        }
        catch(Exception $e)
        {
            echo "Error : " . $e->getMessage();   
        }
    }

    /**
     * Construct custom select request.
     *
     * @param variable
     * @return Base $this
     */
    public static function select()
    {
        static::$select = "SELECT";
        $args = func_get_args();
        for($i = 0; $i < count($args); $i++)
        {
            if($args[$i] == end($args))
            {
                static::$select .= " " . $args[$i] . " FROM ";
            }
            else
            {
                static::$select .= " " . $args[$i] . ",";
            }
        }
        return $this;
    }


    /**
     * Retrieve data by column with this magic method.
     *
     * @param string $column
     * @param string $value
     * @param int [$limit=false]
     * @return stdClass $data
     */
    public static function findBy($column, $value, $limit = false)
    {
        static::reset();
        
        if($limit)
        {
            $limit = " LIMIT " . $limit;    
        }
        static::where($column, "=", $value);
        return static::get();
    }

    /**
     * Allow use magic method 'findBy'.
     *
     * @param callable $name
     * @param array $args
     * @return Closure
     */
    public static function __callStatic($name, $args)
    {
        $class = get_called_class();
        if(substr($name, 0, 6) == 'findBy')
        {
            $field = strtolower(preg_replace('/\B([A-Z])/', '_${1}', substr($name, 6)));
            array_unshift($args, $field);
            return call_user_func_array(array($class, 'findBy'), $args);
        }
    }

    /**
     * Get last inserted id.
     *
     * @param void
     * @return int $lastInsert
     */
    public static function lastInsertId()
    {
        return static::$lastInsert;    
    }

    /**
     * Reset requests variables
     *
     * @param void
     * @return void
     */
    private static function reset()
    {
        static::$select = null;
        static::$clause = "";
        static::$join = "";
        static::$currentQuery = "";
        static::$request = "";
        static::$parameters = "";
    }   
}
