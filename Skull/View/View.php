<?php

namespace Skull\View;

use Skull\Helix\Helix;

class View extends Helix
{
    /**
     * Pass variables to template view.
     *
     * @param mixed
     * @return void
     */
    public static function make()
    {
        $error = null;
        $args = func_get_args();
        $view = $args[0];
        if(count($args) < 2)
        {
            $viewArray = explode('.', $view);
            if(count($viewArray) < 2)
            {
                if(@!include_once(__DIR__ . "/../../app/resources/views/" . $viewArray[0] . '.php'))
                {        
                    $error = "Error : Template " . $viewArray[0] . " doesn't found in resources/views/" . "\n";
                    echo $error;    
                }
            } elseif(count($viewArray) === 2) {
                if(@!include_once(__DIR__ . "/../../app/resources/views/" . $viewArray[0] . "/" . $viewArray[1] . '.php'))
                {
                    $error = "Error : Template " . $viewArray[1] . " doesn't found in resources/views/" . $viewArray[0] . "\n";
                    echo $error;    
                }
            }
        } else {
            $viewArray = explode('.', $view);
            if(count($viewArray) < 2)
            {
                $viewRender = __DIR__ . "/../../app/resources/views/" . $viewArray[0] . '.php';
            } else {
                $viewRender = __DIR__ . "/../../app/resources/views/" . $viewArray[0] . "/" . $viewArray[1] . '.php';
            }
            $result = static::render($viewRender, $args[1]);
            echo $result;
        }
    }
}
