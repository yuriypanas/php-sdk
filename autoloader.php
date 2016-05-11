<?php

foreach (glob(__DIR__ . '/mailfire/*.php') as $filename) {
    require_once $filename;
}
