<?php

namespace Skull\View;

use Skull\Helix\Helix;
use Skull\Http\Path;

class RootView extends Helix
{
    /**
     * Pass variables to template view.
     *
     * @param mixed
     * @return void
     */
    public function make($args)
    {
        $path = new Path();
        $error = null;
        $view = $args[0];
        if(count($args) < 2)
        {
            $viewArray = explode('.', $view);
            if(count($viewArray) < 2)
            {
                if(@!include_once($path->path . "/app/resources/views/" . $viewArray[0] . '.php'))
                {        
                    $error = "Error : Template " . $viewArray[0] . " doesn't found in resources/views/" . "\n";
                    echo $error;    
                }
            } elseif(count($viewArray) === 2) {
                if(@!include_once($path->path . "/app/resources/views/" . $viewArray[0] . "/" . $viewArray[1] . '.php'))
                {
                    $error = "Error : Template " . $viewArray[1] . " doesn't found in resources/views/" . $viewArray[0] . "\n";
                    echo $error;    
                }
            }
        } else {
            $viewArray = explode('.', $view);
            if(count($viewArray) < 2)
            {
                $viewRender = $path->path . "/app/resources/views/" . $viewArray[0] . '.php';
            } else {
                $viewRender = $path->path . "/app/resources/views/" . $viewArray[0] . "/" . $viewArray[1] . '.php';
            }
            $result = $this->render($path, $viewRender, $args[1]);
            echo $result;
        }
    }
}
