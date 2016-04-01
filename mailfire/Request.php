<?php

class MailfireRequest extends MailfireDi
{
    const API_BASE = 'https://api.mailfire.io/v1/';

    public function receive($resource, $data) {
        return $this->send($resource, 'GET', $data);
    }

    public function create($resource, $data) {
        return $this->send($resource, 'POST', $data);
    }

    private function send($resource, $method, $data)
    {
        $resource = strtolower($resource);
        $method = strtoupper($method);
        $uri = self::API_BASE . $resource;

        $headers = [];
        ksort($data);
        $sign = hash_hmac('sha256', json_encode($data), $this->clientKey);
        $data['hash'] = $sign;
//        $headers[] = 'Authorization: Sign ' . $sign;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $result = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($result, true);
        if (!$result) {
            return false;
        }
        return $result;
    }

    private function getSign($uri, $method, $data)
    {
        $data['request_method'] = $method;
        $data['request_uri'] = $uri;
        ksort($data);
        $sign = hash_hmac('sha256', json_encode($data, JSON_UNESCAPED_SLASHES), $this->clientKey);

        $signData = json_encode(['client_id' => $this->clientId, 'sign' => $sign]);
        return base64_encode($signData);
    }
}