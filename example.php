<?php

// If you no have autoloader
foreach (glob('mailfire/*.php') as $filename) {
    require_once $filename;
}

// Creating Mailfire object
$mf = new Mailfire(1, 2);

// Send letter
$res = $mf->push->send(1, 2, 3, 4, 5);
var_dump($res);