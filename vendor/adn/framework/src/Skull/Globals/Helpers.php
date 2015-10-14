<?php

use Skull\Sessions\RootSession;

function session()
{
    $o = new RootSession(false);
    return $o->hydrate();
}
