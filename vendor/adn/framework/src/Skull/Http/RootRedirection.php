<?php

namespace Skull\Http;

class RootRedirection
{
    /**
     * Url to redirect.
     *
     * @var string
     */
    public $url;

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
    public function filterUrl($url)
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    /**
     * Redirect to $url.
     *
     * @param string $url
     * @return void
     */
    public function to($url)
    {
        $epurUrl = $this->filterUrl($url);
        header("Location: " . $epurUrl, true, 302);
        exit();
    }
}
