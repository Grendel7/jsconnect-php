# Vanilla Forums jsConnect for Composer

Rebuild of the official [jsConnect PHP library](https://github.com/vanilla/jsConnectPHP) for Vanilla Forums. PSR compliant and easily installable through Composer.

## Installation
Add the composer dependency:
```bash
composer require hansadema/jsconnect
```

## Usage
The example below shows a standard use case for a logged in user.

```php
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
```

If the user is not logged in, the user object can be left empty. If there is an error, you can return an error with the response data:
```php
$response = [
    'error' => 'invalid_client',
    'message' => 'Your Custom Error Message',
];
```