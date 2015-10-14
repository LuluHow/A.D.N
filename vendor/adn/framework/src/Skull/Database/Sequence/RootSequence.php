<?php

namespace Skull\Database\Sequence;

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
}
