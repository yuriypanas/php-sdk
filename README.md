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

## User info
```php
$projectId = 1;
$user = $mf->user->getByEmail('Test@Example.com', $projectId);
/* Returned array(
    "id":8424,
    "project_id":1,
    "email":"test@example.com",
    "name":"John",
    "gender":"m",
    "country":"UKR",
    "language":"en",
    "last_online":"2016-06-17 12:59:19",
    "last_reaction":"2016-06-17 12:59:19",
    "last_mailed":"2016-06-22 12:06:45",
    "last_request":"2016-06-03 04:01:19",
    "activation":"2016-01-30 11:28:24",
    ...
) */
```

## Unsubscribe
```php
$projectId = 1;
$user = $mf->user->getByEmail('test@example.com', $projectId);
$unsub = $mf->unsub->addBySettings($user);
var_dump($unsub);
```

## Unsubscribe from types
```php
$projectId = 1;
$user = $mf->user->getByEmail('test@example.com', $projectId);
$list = $mf->unsubTypes->getList($user);
var_dump($list);
//array(2) {
//  [0] =>
//  array(3) {
//    'type_id' =>
//   int(3)
//    'unsubscribed' =>
//    bool(false)
//    'name' =>
//    string(11) "Popular now"
//  }
//  [1] =>
//  array(3) {
//    'type_id' =>
//    int(4)
//    'unsubscribed' =>
//    bool(false)
//    'name' =>
//    string(8) "Breaking"
//  }
//}

$result = $mf->unsubTypes->setDisabledTypes(12, [4]); //subscribes
//for all active type_id`s except 4

$mf->unsubTypes->addTypes(12, [4]); //unsubscribe user from type 4
$mf->unsubTypes->removeTypes(12, [4]); //subscribe user for type 4
$mf->unsubTypes->removeAll(12); //subscribe user for all types

```

## Send push notification
```php
//select user
$user = $mf->user->getByEmail('someone@example.com', 2);
//webpush data
$title = 'Webpush title';
$url = 'https://myproject.com/show/42';
$iconUrl = 'https://static.myproject.com/6hgf5ewwfoj6';
$typeId = 6;
//send
$mf->webpush->sendByUser($user, $title, $url, $iconUrl, $typeId);
```

## Unsubscribe/Subscribe user for push notifications
```php
//select user
$user = $mf->user->getByEmail('someone@example.com', 2);
//unsubscribe
$mf->webpush->unsubscribeByUser($user);
//subscribe back
$mf->webpush->subscribeByUser($user);
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
