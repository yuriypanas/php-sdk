<?php

namespace Mailfire;

class Mailfire
{
    public $clientId;
    public $clientKey;

    public function __construct($clientId, $clientKey)
    {
        $this->clientId = $clientId;
        $this->clientKey = $clientKey;

        $this->request = new MailfireRequest($this);
        $this->push = new MailfirePush($this);
        $this->email = new MailfireEmail($this);
    }
}


