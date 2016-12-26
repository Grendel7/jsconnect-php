<?php

namespace HansAdema\JsConnect;

/**
 * Class JsConnect
 *
 * The main jsConnect class.
 *
 * @package HansAdema\JsConnect
 */
class JsConnect
{
    /**
     * @var string The jsConnect Client ID
     */
    protected $clientId;

    /**
     * @var string The jsConnect Client secret
     */
    protected $secret;

    /**
     * @var array The options for jsConnection
     *
     * 'hash_algo': The hash algorithm to use (default: md5)
     * 'timeout': The signature expiration time (default: 24 minutes)
     * 'check_security': Whether the incoming signature should be validated (default: true)
     * 'sign_response': Whether the response should be signed (default: true)
     */
    protected $options;

    /**
     * JsConnect constructor.
     *
     * @param string $clientId
     * @param string $secret
     * @param array $options
     */
    public function __construct($clientId, $secret, $options = [])
    {
        $defaultOptions = [
            'hash_algo' => 'md5',
            'timeout' => 24*60,
            'check_security' => true,
            'sign_response' => true,
        ];

        $this->options = array_merge($defaultOptions, $options);

        $this->clientId = $clientId;
        $this->secret = $secret;
    }

    /**
     * Build a new jsConnect response
     *
     * @param User $user The user data container
     * @param array $request The GET request data, defaults to $_GET
     *
     * @return array The JSONP data
     * @throws RequestException If the input data is invalid, an exception is thrown which should be returned as JSONP
     */
    public function buildResponse(User $user, $request = null)
    {
        if ($request === null) {
            $request = $_GET;
        }

        if ($this->options['check_security']) {
            if (!isset($request['client_id'])) {
                throw new RequestException('invalid_request', 'The client_id parameter is missing.');
            } elseif ($request['client_id'] != $this->clientId) {
                throw new RequestException('invalid_client', "Unknown client {$request['client_id']}.");
            } elseif (!isset($request['timestamp']) && !isset($request['signature'])) {
                // This isn't really an error, but we are just going to return public information when no signature is sent.
                return $user->getResponseData();
            } elseif (!isset($request['timestamp']) || !is_numeric($request['timestamp'])) {
                throw new RequestException('invalid_request', 'The timestamp parameter is missing or invalid.');
            } elseif (!isset($request['signature'])) {
                throw new RequestException('invalid_request', 'Missing  signature parameter.');
            } elseif (abs($request['timestamp'] - $this->timestamp()) > $this->options['timeout']) {
                throw new RequestException('invalid_request', 'The timestamp is invalid.');
            } elseif ($this->hash($request['timestamp']) != $request['signature']) {
                throw new RequestException('access_denied', 'Signature invalid.');
            }
        }

        if ($this->options['sign_response']) {
            return $this->sign($user->getResponseData());
        } else {
            return $user->getResponseData();
        }
    }

    /**
     * Add a signature and client id to an array of user data
     *
     * @param array $data
     * @return array
     */
    protected function sign($data)
    {
        $string = http_build_query($data, null, '&');

        $data['client_id'] = $this->clientId;
        $data['signature'] = $this->hash($string);

        return $data;
    }

    /**
     * Hash a given string
     *
     * @param string $string
     *
     * @return string
     */
    protected function hash($string)
    {
        return hash($this->options['hash_algo'], $string . $this->secret);
    }

    /**
     * Get a timestamp
     *
     * @return int
     */
    protected function timestamp()
    {
        return time();
    }
}