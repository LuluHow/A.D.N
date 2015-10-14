<?php

namespace Skull\Http;

class Redirection
{
    /**
     * Url to redirect.
     *
     * @var string
     */
    public static $url;

    /**
     * Create new instance of Redirection.
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        //    
    }

    /**
     * Url filter.
     *
     * @param string $url
     * @return string
     */
    public static function filterUrl($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Redirect to $url.
     *
     * @param string $url
     * @return void
     */
    public static function to($url)
    {
        $epurUrl = static::filterUrl($url);
        header("Location: " . $epurUrl, true, 302);
        exit();
    }
}
