<?php

class MailfireErrorHandler
{
    private $throwExceptions;

    public function __construct($throwExceptions = false)
    {
        $this->throwExceptions = $throwExceptions;
    }

    public function handle(Exception $e)
    {
        if ($this->throwExceptions){
            throw $e;
        } else{
            $template = ':time Mailfire: [:type] :message in :file in line :line';
            $logMessage = strtr($template, array(
                ':time'    => date('Y-m-d H:i:s'),
                ':type'    => $e->getCode(),
                ':message' => $e->getMessage(),
                ':file'    => $e->getFile(),
                ':line'    => $e->getLine()
            ));
            error_log($logMessage) ;
        }
    }
}


