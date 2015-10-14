<?php

namespace Facades;

use Skull\Database\Sequence\RootSequence;

class Sequence
{
    public static function create(array $inputs)
    {
        $model = get_called_class();
        $o = new RootSequence;
        return $o->create($inputs, $model);
    }

    public static function all()
    {
        $model = get_called_class();
        $o = new RootSequence;
        return $o->all($model);
    }

    public static function findBy($column, $value, $limit = false)
    {
        $model = get_called_class();
        $o = new RootSequence;
        return $o->findBy($column, $value, $limit, $model);
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
}
