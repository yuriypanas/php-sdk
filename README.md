# Mailfire PHP SDK

[![Latest Stable Version](https://poser.pugx.org/mailfire/php-sdk/v/stable)](https://packagist.org/packages/mailfire/php-sdk)
[![Total Downloads](https://poser.pugx.org/mailfire/php-sdk/downloads)](https://packagist.org/packages/mailfire/php-sdk)

PHP SDK for https://mailfire.io

## Install
by composer from https://packagist.org/packages/mailfire/php-sdk
```sh
composer require mailfire/php-sdk
```
or just include autoload:
```php
require_once 'autoloader.php';
```

# Using
```php
// Init Mailfire SDK object
$clientId = 123;
$clientHash = 'a1s2d3f4g5h6j7k8l';
$mf = new Mailfire($clientId, $clientHash);
```

## Send email
```php
$typeId = 1; // letter id
$categoryId = $mf->push->getCategorySystem(); // system or trigger
$projectId = 1;
$email = 'test@example.com';

$user = [ // User info, if you know
    'name' => 'John',
    'age' => '22',
    'gender' => 'm',
    'language' => 'en',
    'country' => 'US',
    'platform_id' => $mf->user->getPlatformDesktop(),
    'vip' => 0,
    'photo' => 'http://example.com/somephotourl.jpg',
];

$data = [ // Data for letter
    'some' => 'hi',
    'letter' => 'John',
    'variables' => '!',
];

$meta = []; // Your additional data

$response = $mf->push->send($typeId, $categoryId, $projectId, $email, $user, $data, $meta);
var_dump($response);
```

## Check email
```php
$result = $mf->email->check('Test@Example.com');
/* Returned array(
  'orig' => 'Test@Example.com',
  'valid' => false, // result
  'reason' => 'mx_record', // reason of result
  'email' => 'test@example.com', // fixed email
  'vendor' => 'Unknown', // vendor name like Gmail
  'domain' => 'example.com',
  'trusted' => false,
) */
```

## Unsubscribe
```php
$projectId = 1;
$user = $mf->user->getByEmail('test@example.com', $projectId);
$unsub = $mf->unsub->addBySettings($user);
var_dump($unsub);
```

## Error handling
By default any error messages (except InvalidArgumentException in Mailfire constructor) collects in error_log.
If you want the component throws exceptions just change handler mode:
```php
$mf = new Mailfire($clientId, $clientHash);
$mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
```

# HOW TO RUN THE TESTS
Make sure you have PHPUnit installed.

Run PHPUnit in the mailfire repo base directory.
```bash
./test.sh
```
