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
            $unescaped = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $unescaped = $this->jsonEncodeUnescapedUnicode($data);
            //simulate JSON_UNESCAPED_SLASHES
            $unescaped = str_replace('\\/', '/', $unescaped);
        }

        $sign = hash_hmac('sha256', $unescaped, $this->clientKey);

        $signData = json_encode(array('client_id' => $this->clientId, 'sign' => $sign));
        return base64_encode($signData);
    }

    private function jsonEncodeUnescapedUnicode($arr)
    {
        if (!function_exists('mb_encode_numericentity')) {
            $exception = new Exception('Ext mbstring is required');
            $this->errorHandler->handle($exception);
            return false;
        }
        //convmap since 0x80 char codes so it takes all multibyte codes (above ASCII 127). So such characters are being "hidden" from normal json_encoding
        array_walk_recursive($arr, function (&$item, $key) {
            if (is_string($item)) $item = mb_encode_numericentity($item, array(0x80, 0xffff, 0, 0xffff), 'UTF-8');
        });
        return mb_decode_numericentity(json_encode($arr), array(0x80, 0xffff, 0, 0xffff), 'UTF-8');

    }
}
