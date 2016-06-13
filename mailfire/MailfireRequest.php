<?php

class MailfireRequest extends MailfireDi
{
    const API_BASE = 'https://api.mailfire.io/v1/';

    private $curlRequest = null;

    public function __construct($di)
    {
        parent::__construct($di);
        $this->setCurlRequest(new MailfireCurlRequest());
    }

    public function setCurlRequest(MailfireCurlRequest $curlRequest)
    {
        $this->curlRequest = $curlRequest;
    }

    public function receive($resource, array $data = array())
    {
        return $this->send($resource, 'GET', $data);
    }

    public function create($resource, array $data = array())
    {
        return $this->send($resource, 'POST', $data);
    }

    public function update($resource, array $data)
    {
        return $this->send($resource, 'PUT', $data);
    }

    public function delete($resource)
    {
        return $this->send($resource, 'DELETE');
    }

    private function send($resource, $method, $data = array())
    {
        $resource = strtolower($resource);
        $method = strtoupper($method);
        $uri = self::API_BASE . $resource;

        $headers = array();
        $sign = $this->getSign($uri, $method, $data);
        if (false === $sign) {
            return false;
        }
        $headers[] = 'Authorization: Sign ' . $sign;

        $result = $this->sendCurl($uri, $method, $data, $headers);
        if ($result['code'] != 200) {
            $debugData = array(
                'uri' => $uri,
                'method' => $method,
                'data' => $data,
                'headers' => $headers
            );
            $exception = new Exception('Request failed: ' . json_encode($result) .
                ' Request data: ' . json_encode($debugData));
            $this->errorHandler->handle($exception);
            return false;
        }
        $result = json_decode($result['result'], true);
        if (!$result) {
            return false;
        }
        if (isset($result['data'])) {
            return $result['data'];
        }
        return true;
    }

    private function sendCurl($uri, $method, $data, $headers)
    {
        $this->curlRequest->setOption(CURLOPT_URL, $uri);
        if (count($data)) {
            $this->curlRequest->setOption(CURLOPT_POSTFIELDS, json_encode($data));
        }
        $this->curlRequest->setOption(CURLOPT_HTTPHEADER, $headers);
        $this->curlRequest->setOption(CURLOPT_RETURNTRANSFER, 1);
        $this->curlRequest->setOption(CURLOPT_CUSTOMREQUEST, $method);
        $result = $this->curlRequest->execute();
        $code = $this->curlRequest->getInfo(CURLINFO_HTTP_CODE);

        return array(
            'result' => $result,
            'code' => $code
        );
    }

    private function getSign($uri, $method, $data)
    {
        $data['request_method'] = $method;
        $data['request_uri'] = $uri;
        ksort($data);

        if (PHP_VERSION_ID >= 50400) {
            $unescaped = json_encode($data, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        } else {
            if (!function_exists('mb_convert_encoding')) {
                $exception = new Exception('Ext mbstring is required on php < 5.4');
                $this->errorHandler->handle($exception);
                return false;
            }
            //There is no JSON_UNESCAPED_SLASHES and JSON_UNESCAPED_UNICODE in php5.3
            $encoded = json_encode($data);

            //simulate JSON_UNESCAPED_UNICODE
            $unescaped = preg_replace_callback(
                '/(?<!\\\\)\\\\u(\w{4})/i',
                function ($matches) {
                    $sym = mb_convert_encoding(
                        pack('H*', $matches[1]),
                        'UTF-8',
                        'UTF-16'
                    );
                    return $sym;
                },
                $encoded
            );

            //simulate JSON_UNESCAPED_SLASHES
            $unescaped = str_replace('\\/', '/', $unescaped);
        }

        $sign = hash_hmac('sha256', $unescaped, $this->clientKey);

        $signData = json_encode(array('client_id' => $this->clientId, 'sign' => $sign));
        return base64_encode($signData);
    }
}
