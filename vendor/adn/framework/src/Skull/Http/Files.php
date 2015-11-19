<?php

namespace Skull\Http;

class Files
{
    /**
     *
     *
     *
     */
    public $files;

    /**
     * Create new instance of Files.
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->files = $_FILES;
    }
}
