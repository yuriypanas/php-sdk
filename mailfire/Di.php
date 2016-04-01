<?php

namespace Mailfire;

/**
 * Class MailfireDi
 *
 * @property \Mailfire\MailfirePush push
 * @property \Mailfire\MailfireRequest request
 *
 */

class MailfireDi
{
    public $di = null;

    public function __construct($di)
    {
        $this->di = $di;
    }

    public function __get($propertyName)
    {
        if (isset($this->di->{$propertyName})) {
            $service = $this->di->{$propertyName};
            $this->{$propertyName} = $service;
            return $service;
        } else {
            return null;
        }
    }
}