<?php

namespace Help\Session;

use Skull\Sessions\RootSession;

class Session
{
    /**
     * Set new value in session storage.
     *
     * @param string $key
     * @param mixed $value
     * @return array $key
     */
    public static function set($key, $value)
    {
        $o = new RootSession;
        $o->set($key, $value);
    }

    /**
     * Update value in session storage.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function put($key, $value)
    {
        $o = new RootSession;
        $o->put($key, $value);
    }

    /**
     * Destroy session value.
     *
     * @param string $key
     * @return bool
     */
    public static function destroy($key)
    {
        $o = new RootSession;
        $o->destroy($key);
    }

    /**
     * Retrieve session value and destroy it.
     *
     * @param string $key
     * @return mixed $value
     * @return bool false
     */
    public static function getAndDestroy($key)
    {
        $o = new RootSession;
        $o->getAndDestroy($key);
    }

    /**
     * Destroy session.
     *
     * @param void
     * @return void
     */
    public static function flush()
    {
        $o = new RootSession;
        $o->flush();
    }

    /**
     * Get session value.
     *
     * @param string $key
     * @return mixed $value
     */
    public static function get($key)
    {
        $o = new RootSession;
        $o->get($key);
    }
}
