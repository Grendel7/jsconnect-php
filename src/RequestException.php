<?php

namespace HansAdema\JsConnect;

/**
 * Class RequestException
 *
 * This exception is thrown whenever there is some invalid data with the SSO request.
 *
 * jsConnect requires a JSON response object with the parameters error and message.
 *
 * @package HansAdema\JsConnect
 */
class RequestException extends \Exception
{
    protected $error;

    /**
     * Build a new Request Exception
     *
     * @param string $error
     * @param string $message
     */
    public function __construct($error, $message)
    {
        parent::__construct($message);

        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}