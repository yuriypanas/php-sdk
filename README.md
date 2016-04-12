# Mailfire PHP SDK

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
$mf = new \Mailfire\Mailfire($clientId, $clientHash);
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
$result = $mf->email->check('test@example.com');
var_dump($result);
```

## Unsubscribe
```php
$projectId = 1;
$user = $mf->user->getByEmail('test@example.com', $projectId);
$unsub = $mf->unsub->addBySettings($user);
var_dump($unsub);
```
