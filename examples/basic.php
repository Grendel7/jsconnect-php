<?php

require_once '../vendor/autoload.php';

// Setup a Jsconnect instance
$jsConnect = new \HansAdema\JsConnect\JsConnect('YOUR CLIENT ID', 'YOUR CLIENT SECRET');

// Build a user object
$user = new \HansAdema\JsConnect\User([
    'id' => 1234,
    'name' => 'Example User',
    'email' => 'user@example.com',
    'photoUrl' => 'http://example.com/user.jpg',
    'roles' => ['member', 'administrator'],
]);

// Try to build the response
$response = $jsConnect->buildResponse($user, $_GET);

// Return the JSONP result
echo $_GET['callback'].'('.json_encode($response).')';