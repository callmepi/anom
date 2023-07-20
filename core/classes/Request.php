<?php
namespace anom\core;

class Request 
{
    
    public $URL;        // full request url

    public $PATH;       // Path (decoded)

    public $QUERY;      // Query string (decoded)

    public $HOST;

    public $PORT;

    public $METHOD;     // request method

    public $TIME;       // request timestamp

    public $IP;         // Client's IP address

    public $AGENT;      // Client's User Agent

    public $GET = [];   // $_GET array

    public $POST = [];  // $_POST array

    public $FILES = []; // $_FILES array

    public $SIGNATURE;  // user/client's device signature



    public function __construct($errors = false)
    {
        $parsed = parse_url($_SERVER['REQUEST_URI']);
        $this->URL     = $_SERVER['REQUEST_URI'];
        $this->PATH    = (!empty($parsed['path'])) ? urldecode($parsed['path']) : '';
        $this->QUERY   = (!empty($parsed['query'])) ? urldecode($parsed['query']) : false;
        $this->HOST    = $_SERVER['HTTP_HOST'];
        $this->PORT    = $_SERVER['SERVER_PORT'];
        $this->TIME    = $_SERVER['REQUEST_TIME'];
        $this->IP      = $_SERVER['REMOTE_ADDR'];

        $this->METHOD = strtolower($_SERVER['REQUEST_METHOD']);

        $this->GET = $_GET;     // $_GET should only used
            // to request data or specify options (never to perform
            // system-changes) thus should not need any validation;
            // * If (for any reason) you requide $_GET sanitization
            // enable it later on the method's code

        $this->FILES = $_FILES;     // TODO: sanitize the files array

        // $this->INTERFACE = php_sapi_name();

        $this->AGENT   = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        $this->SIGNATURE = sha1(
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
                . $_SERVER['HTTP_ACCEPT'] ?? ''
                . $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''
                . $_SERVER['HTTP_ACCEPT_ENCODING'] ?? ''
        );

        // sanitize user input
        // if (isset($_GET))     {  $this->GET = $this->sanitize($_GET); }
        $this->GET = $this->sanitize($_GET);
        if (isset($_POST))    {  $this->POST = $this->sanitize($_POST); }
        if (isset($_COOKIE))  {  $this->COOKIE = $this->sanitize($_COOKIE); }

        // check anti-CSRF token if needed
        // (again, GET requests should not need CSRF cheking)
        if (in_array($this->METHOD, ['post', 'put', 'patch', 'delete'])) {
            // TODO: only if CSRF protection enabled...
            $this->checkCsrfToken();
        }

    }


    /**
     * Check:
     * 
     * 1.
     * https://dev.to/anastasionico/good-practices-how-to-sanitize-validate-and-escape-in-php-3-methods-139b
     * 
     * 2.
     * https://benhoyt.com/writings/dont-sanitize-do-escape/
     * 
     */
    private function sanitize($array)
    {
        // TODO:
        // ...
        return $array;
    }

    
    public function checkCsrfToken()
    {
        // TODO:
        // ...
        // if SCRF-token is not valideted, serve 403
        return true;
    }




    public function isAjax(): bool
    {
        // check headers 'XMLHttpRequest' == $this->headers->get('X-Requested-With');
    }


    public function isSecure(): bool
    {
        // chech if HTTPS
    }


    public function hasSession(): bool
    {
        // chech if Session exist
    }


    /**
     * Get the user making the request.
     *
     * @param  string|null  $guard
     * @return mixed
     */
    public function user($guard = null)
    {
        // return call_user_func($this->getUserResolver(), $guard);
    }

    public function getUserResolver()
    {
        // return $this->userResolver ?: function () {
        //
        // };
    }

    /**
     * Set the user resolver callback.
     *
     * @param  \Closure  $callback
     * @return $this
     */
    public function setUserResolver(Closure $callback)
    {
        // $this->userResolver = $callback;
        // return $this;
    }
  
}
