<?php

namespace Mailfire;

class Mailfire
{
    public $clientId;
    public $clientKey;

    const CATEGORY_SYSTEM = 1;
    const CATEGORY_TRIGGER = 2;

    public function __construct($clientId, $clientKey)
    {
        $this->clientId = $clientId;
        $this->clientKey = $clientKey;

        $this->request = new MailfireRequest($this);
        $this->push = new MailfirePush($this);
        $this->email = new MailfireEmail($this);
    }
}


