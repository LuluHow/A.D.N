<?php

namespace Skull\Sessions;

use Skull\Http\Path;
use Skull\Genetic\RandomGenerator;

class RootSession
{
    /**
     * Create new instance of Session.
     *
     * @param void
     * @return void
     */
    public function __construct($flag = true)
    {
        if($flag)
        {
            $generator = new RandomGenerator;

            $time = $this->getSessionTimeExpire();

            if(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $time))
            {
                session_unset();
                session_destroy();
            } elseif(!isset($_SESSION['_id'])) {
                $_SESSION['last_activity'] = time();
                $_SESSION['_id'] = $generator->string(20);
            } else {
                $_SESSION['last_activity'] = time();
            }
        }
    }

    /**
     * Set new value in session storage.
     *
     * @param string $key
     * @param mixed $value
     * @return array $key
     */
    public function set($key, $value)
    {
        if(isset($_SESSION[$key]) && !empty($_SESSION[$key]))
        {
            echo "Error : session key [$key] already exists";
            exit();
        } else {
            $_SESSION[$key] = $value;
            return $_SESSION[$key];
        }
    }

    /**
     * Update value in session storage.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function put($key, $value)
    {
        $_SESSION[$key] = $value;
        return $_SESSION[$key];
    }

    /**
     * Destroy session value.
     *
     * @param string $key
     * @return bool
     */
    public function destroy($key)
    {
        if(isset($_SESSION[$key]))
        {
            unset($_SESSION[$key]);
            return true;    
        }
        return false;
    }

    /**
     * Retrieve session value and destroy it.
     *
     * @param string $key
     * @return mixed $value
     * @return bool false
     */
    public function getAndDestroy($key)
    {
        if(isset($_SESSION[$key]))
        {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }
        return false;
    }

    /**
     * Destroy session.
     *
     * @param void
     * @return void
     */
    public function flush()
    {
        session_unset();    
        session_destroy();
    }

    /**
     * Get session value.
     *
     * @param string $key
     * @return mixed $value
     */
    public function get($key)
    {
        if(isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        return false;
    }

    /**
     * Hydrate object with all session values.
     *
     * @param void
     * @return Session $object
     */
    public function hydrate()
    {
        foreach($_SESSION as $key => $value)
        {
            $this->$key = $value;
        }
        return $this;
    }

    /**
     * Return sessions time expire, in secondes.
     *
     * @param void
     * @return int $timeExpire
     */
    public function getSessionTimeExpire()
    {
        $configPath = new Path;

        include_once($configPath->path . '/config/sessions.php');
        $timeExpire = $sessions['expires'];
        return $timeExpire * 60;
    }
}
