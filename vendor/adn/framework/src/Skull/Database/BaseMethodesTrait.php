<?php

namespace Skull\Database;

use Skull\Database\ConnectFactory;
use PDO;

trait BaseMethodesTrait
{
     /**
     * Object contain connection.
     *
     * @var PDO 
     */
    public $connect;

    /**
     * Contain current request.
     *
     * @var PDO 
     */
    public $currentQuery;

    /**
     * Contain the request.
     *
     * @var string
     */
    public $request;

    /**
     * Contain the results.
     *
     * @var stdClass
     */
    public $data;

    /**
     * Contain ID of last inserted row.
     *
     * @var int
     */
    public $lastInsert;

    /**
     * Contain join clause(s)
     *
     * @var string
     */
    public $join;

    /**
     * Contain current table for use.
     *
     * @var string
     */
    public $table;

    /**
     * WHERE clause.
     *
     * @var string
     */
    public $clause;

    /**
     * Condition for WHERE clause.
     *
     * @var string
     */
    public $parameters;

    /**
     * SELECT clause.
     *
     * @var string
     */
    public $select;

    /**
     * Initialize model.
     *
     * @param string $table
     * @return void
     */
    public function construct($table) 
    {
        $this->connect = ConnectFactory::getConnect();
        $this->table = $table;
        $this->select = null;
        $this->clause = "";
        $this->join = "";
    }
    
    /**
     * Construct WHERE clause.
     *
     * @param string $clause
     * @param string $operande
     * @param string $parameter
     * @return $this
     */
    public function where($clause, $operande, $parameter)
    {
        $this->clause = " WHERE " . $clause . " " . $operande . " ?";
        $this->parameters = $parameter;
        return $this;
    }

    /**
     * Construct request.
     *
     * @param int $limit
     * @return void
     */
    public function constructSelectRequest($limit = false)
    {
        if($limit)
        {
            $limit = " LIMIT " . $limit;    
        }
        if($this->select == null && $this->request == null)
        {
            $this->request = "SELECT * FROM " . $this->table . $this->join . $this->clause . $limit;
        }
        else
        {
            $this->request = $this->select . $this->table . $this->join . $this->clause . $limit;    
        }
    }

    /**
     * Prepare request.
     *
     * @param void
     * @return PDO $query
     */
    public function prepareRequest()
    {
        try
        {
            $this->currentQuery = $this->connect->getConnection()->prepare($this->request);
            return $this->currentQuery;
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
    public function executeRequest()
    {
        try 
        {
            if(is_array($this->parameters))
            {
                $this->currentQuery->execute($this->parameters);
            }
            else
            {
                $this->currentQuery->execute(array($this->parameters));
            }
            return $this->currentQuery;
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
    public function get($limit = false)
    {
        if($this->request == null)
        {
            $this->constructSelectRequest($limit);
        }
        $this->prepareRequest();
        try
        {
            $this->data = $this->executeRequest()->fetchAll(PDO::FETCH_OBJ);
            if(count($this->data) === 1)
            {
                $this->data = $this->data[0];    
            }
            return $this->data;
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
    private function constructInsertRequest(array $array)
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
        $this->request = "INSERT INTO " . $this->table . $fields . " VALUES" . $values;
        $this->parameters = $insertRows;
    }

    /**
     * Perform insert query.
     *
     * @param array $array 
     * @return Base $this
     */ 
    public function insert(array $array)
    {
        $insertRows = $this->constructInsertRequest($array);
        try
        {
            $this->prepareRequest();
            $this->executeRequest();
            $this->lastInsert = $this->connect->getConnection()->lastInsertId();
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
    public function select()
    {
        $this->select = "SELECT";
        $args = func_get_args();
        for($i = 0; $i < count($args); $i++)
        {
            if($args[$i] == end($args))
            {
                $this->select .= " " . $args[$i] . " FROM ";
            }
            else
            {
                $this->select .= " " . $args[$i] . ",";
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
    public function findBy($column, $value, $limit = false)
    {
        $this->reset();
        
        if($limit)
        {
            $limit = " LIMIT " . $limit;    
        }
        $this->where($column, "=", $value);
        return $this->get();
    }

    /**
     * Allow use magic method 'findBy'.
     *
     * @param callable $name
     * @param array $args
     * @return Closure
     */
    public function __call($name, $args)
    {
        $class = get_called_class();
        if(substr($name, 0, 6) == 'findBy')
        {
            $field = strtolower(preg_replace('/\B([A-Z])/', '_${1}', substr($name, 6)));
            array_unshift($args, $field);
            return call_user_func_array(array($class, 'findBy'), $args);
        }
    }

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
    public function lastInsertId()
    {
        return $this->lastInsert;    
    }

    /**
     * Reset requests variables
     *
     * @param void
     * @return void
     */
    private function reset()
    {
        $this->select = null;
        $this->clause = "";
        $this->join = "";
        $this->currentQuery = "";
        $this->request = "";
        $this->parameters = "";
    }   
}
