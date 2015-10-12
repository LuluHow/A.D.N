<?php

namespace Skull\Database\Sequence;

class Sequence
{
    use \Skull\Database\StaticBaseMethodesTrait;

    /**
     * Create new entry.
     *
     * @param array $inputs
     * @return Model
     */
    public static function create(array $inputs)
    {
        $absoluteModel = get_called_class();
        $modelArray = explode("\\", $absoluteModel);
        $model = end($modelArray);
        $model = strtolower($model) . 's';

        static::construct($model);
        static::insert($inputs);
        $newObject = static::findById(static::$lastInsert);
        return $newObject;
    }

    /**
     * Retrieve all entry from a model.
     *
     * @param void
     * @return Model
     */
    public static function all()
    {
        $absoluteModel = get_called_class();
        $modelArray = explode("\\", $absoluteModel);
        $model = end($modelArray);
        $model = strtolower($model) . 's';

        static::construct($model);   
        $newObject = static::get();
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
    public static function findBy($column, $value, $limit = false)
    {
        $absoluteModel = get_called_class();
        $modelArray = explode("\\", $absoluteModel);
        $model = end($modelArray);
        $model = strtolower($model) . 's';

        static::construct($model);
        static::reset();
        
        if($limit)
        {
            $limit = " LIMIT " . $limit;    
        }
        static::where($column, "=", $value);
        $newObject = static::get();
        return $newObject;
    }
}