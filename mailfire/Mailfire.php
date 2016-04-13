<?php

class Mailfire
{
    public $clientId;
    public $clientKey;

    public function __construct($clientId, $clientKey, $throwExceptions = false)
    {
        $this->errorHandler = new MailfireErrorHandler($throwExceptions);
        if (empty($clientId)){
            $ex = new InvalidArgumentException('clientId cannot be empty');
            $this->errorHandler->handle($ex);
        }
        if (empty($clientKey)){
            $ex =  new InvalidArgumentException('clientKey cannot be empty');
            $this->errorHandler->handle($ex);
        }
        if (!is_string($clientKey)){
            $ex =  new InvalidArgumentException('clientKey must be a string');
            $this->errorHandler->handle($ex);
        }

        $this->clientId = $clientId;
        $this->clientKey = $clientKey;

        $this->request = new MailfireRequest($this);
        $this->push = new MailfirePush($this);
        $this->email = new MailfireEmail($this);
        $this->user = new MailfireUser($this);
        $this->unsub = new MailfireUnsub($this);
    }
}


