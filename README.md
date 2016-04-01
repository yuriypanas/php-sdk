# Mailfire PHP SDK

## Install
```sh
composer require mailfire/php-sdk
```
or just include autoload:
```php
require_once 'autoloader.php';
```

## Using
```php
$clientId = 123;
$clientHash = 'a1s2d3f4g5h6j7k8l';
$mf = new Mailfire($clientId, $clientHash);
$result = $mf->email->check('test@example.com');
var_dump($result);
```
