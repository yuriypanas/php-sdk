<?php

class MailfireCurlRequest
{
    private $handle = null;
    public $resetOptions = true;

    public function __construct($url = '')
    {
        $this->handle = curl_init($url);
    }

    public function setOption($name, $value)
    {
        curl_setopt($this->handle, $name, $value);
    }

    public function setExtraOption($name, $value, $resetOptions = true)
    {
        $this->resetOptions = $resetOptions;
        curl_setopt($this->handle, $name, $value);
    }

    public function execute()
    {
        return curl_exec($this->handle);
    }

    public function getInfo($name)
    {
        return curl_getinfo($this->handle, $name);
    }

    public function close()
    {
        curl_close($this->handle);
    }

    public function reset()
    {
        curl_reset($this->handle);
    }

}
