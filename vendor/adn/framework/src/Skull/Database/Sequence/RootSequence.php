<?php

namespace Skull\Database\Sequence;

use stdClass;

class RootSequence
{
    use \Skull\Database\BaseMethodesTrait;

    /**
     * Create new instance of Sequence.
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        //    
    }

    /**
     * Create new entry.
     *
     * @param array $inputs
     * @return Model
     */
    public function create(array $inputs, $absoluteModel)
    {
        $modelArray = explode("\\", $absoluteModel);
        $model = end($modelArray);
        $model = strtolower($model) . 's';
        if(!$this->connect)
        {
            $this->construct($model);
        }
        $this->insert($inputs);
        $newObject = $this->findById($this->lastInsert, false, $model);
        return $newObject;
    }

    /**
     * Retrieve all entry from a model.
     *
     * @param void
     * @return Model
     */
    public function all($absoluteModel)
    {
        $modelArray = explode("\\", $absoluteModel);
        $model = end($modelArray);
        $model = strtolower($model) . 's';
        if(!$this->connect)
        {
            $this->construct($model);
        }
        $newObject = $this->get();
        if(count($newObject) < 2)
        {
            return [$newObject];
        }
        return $newObject;
    }

    /**
     * Overriding magic function.
     *
     * @param string
     * @param mixed
     * @param optionnal
     * @return Model
     */
    public function findBy($column, $value, $limit = false, $absoluteModel)
    {
        $modelArray = explode("\\", $absoluteModel);
        $model = end($modelArray);
        $model = strtolower($model) . 's';
        if(!$this->connect)
        {
            $this->construct($model);
        }
        $this->reset();
        
        if($limit)
        {
            $limit = " LIMIT " . $limit;    
        }
        $this->where($column, "=", $value);
        $newObject = $this->get();
        return $newObject;
    }

    public function save(stdClass $object, $absoluteModel)
    {
        $parameters = [];
        $modelArray = explode("\\", $absoluteModel);
        $model = end($modelArray);
        $model = strtolower($model) . 's';
        if(!$this->connect)
        {
            $this->construct($model);
        }
        $this->reset();
        
        $query = 'UPDATE ' . $model . ' SET ';
        foreach($object as $key => $value)
        {
            if($value === end($object))
            {
                $query .= $key . '=:' . $key . ' WHERE id=:id';
            } else {
                $query .= $key . '=:' . $key . ', ';
            }
            $parameters[':' . $key] = $value;
        }
        $req = $this->connect->getConnection()->prepare($query);
        $req->execute($parameters);
        return true;
    }

    public function destroy(stdClass $object, $absoluteModel)
    {
        $modelArray = explode("\\", $absoluteModel);
        $model = end($modelArray);
        $model = strtolower($model) . 's';
        if(!$this->connect)
        {
            $this->construct($model);
        }
        $this->reset();
        $req = $this->connect->getConnection()->prepare('DELETE FROM ' . $model . ' WHERE id = ?');

        $req->execute(array($object->id));

        return true;
    }
}
