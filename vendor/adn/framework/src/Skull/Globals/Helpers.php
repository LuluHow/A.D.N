<?php

use Skull\Sessions\RootSession;
use Skull\Http\Files;
use Skull\Http\Path;

function session()
{
    $o = new RootSession(false);
    return $o->hydrate();
}

function upload(Files $files, $path, $uniqId = false)
{
    $ret = [];
    $base = new Path;
    if(!is_dir($base->path . '/app/resources/storage/' . $path))
    {
        mkdir($base->path . '/app/resources/storage/' . $path, 0777, true);
    }

    foreach($files->files AS $file)
    {
        if($uniqId === true)
        {
            $name = md5(uniqid(rand(), true));
        } else {
            $name = $file['name'];
        }
        $ext = strtolower(substr(strrchr($file['name'], '.'),1));
        $realPath = $base->path . '/app/resources/storage/' . $path . '/' . $name;

        $result = move_uploaded_file($file['tmp_name'], $realPath);
        if ($result)
        {
            array_push($ret, [$file['name'] => [
                "path" => $realPath,
                "extension" => $ext,
                "size" => $file['size']
            ]]);
        } else {
            return false;    
        }
    }
    return $ret;
}
