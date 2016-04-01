<?php

foreach (glob('mailfire/*.php') as $filename) {
    require_once $filename;
}
