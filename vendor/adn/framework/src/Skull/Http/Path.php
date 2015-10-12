<?php

namespace Skull\Http;

class Path
{
    /**
     * Base path of application.
     *
     * @var string
     */
    public $path;

    /**
     * Create new instance of Path.
     *
     * @param string
     * @return void
     */
    public function __construct()
    {
        $this->path = getcwd();
    }
}
