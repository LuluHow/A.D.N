<?php

namespace Skull\Http;

class Request
{
    /**
     * Document root.
     *
     * @var string
     */
    public $documentRoot;

    /**
     * Remote addr.
     *
     * @var string
     */
    public $remoteAddr;

    /**
     * Protocol.
     *
     * @var string
     */
    public $protocol;

    /**
     * Request URI.
     *
     * @var string
     */
    public $uri;

    /**
     * Request method.
     *
     * @var string
     */
    public $method;

    /**
     * Array of post values.
     *
     * @var array
     */
    public $inputs;

    /**
     * Create new instance of Request.
     *
     * @param array $request
     * @return void
     */
    public function __construct(array $request)
    {
        $this->documentRoot     = $request["DOCUMENT_ROOT"];
        $this->remoteAddr       = $request["REMOTE_ADDR"];
        $this->protocol         = $request["SERVER_PROTOCOL"];
        $this->uri              = $request["REQUEST_URI"];
        $this->method           = $request["REQUEST_METHOD"];
        $this->inputs           = $_POST;
        $this->removeTrailingSlash();
    }

    /**
     * Remove trailing slash from requested urls.
     *
     * @param string $uri
     * @return void
     */
    private function removeTrailingSlash()
    {
        if(substr($this->uri, -1) == "/" && strlen($this->uri) > 1)
        {
            $this->uri = substr($this->uri, 0, -1);    
        }
    }

    /**
     * Put all posts inputs in array.
     *
     * @params void
     * @return array
     */
    public function all()
    {
        return $this->inputs;    
    }
}
