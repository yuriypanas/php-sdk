<?php

namespace Mailfire;

class MailfireRequest extends MailfireDi
{
    const API_BASE = 'https://api.mailfire.io/v1/';

    public function receive($resource, $data = [])
    {
        return $this->send($resource, 'GET', $data);
    }

    public function create($resource, $data = [])
    {
        return $this->send($resource, 'POST', $data);
    }

    public function update($resource, $data)
    {
        return $this->send($resource, 'PUT', $data);
    }

    public function delete($resource)
    {
        return $this->send($resource, 'DELETE');
    }

    private function send($resource, $method, $data = [])
    {
        $resource = strtolower($resource);
        $method = strtoupper($method);
        $uri = self::API_BASE . $resource;

        $headers = [];
        $sign = $this->getSign($uri, $method, $data);
        $headers[] = 'Authorization: Sign ' . $sign;

        $result = $this->sendCurl($uri, $method, $data, $headers);
        if ($result['code'] != 200) {
            error_log('Mailfire: ' . $result['result']);
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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $uri);
        if (count($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return [
            'result' => $result,
            'code' => $code,
        ];
    }

    private function getSign($uri, $method, $data)
    {
        $data['request_method'] = $method;
        $data['request_uri'] = $uri;
        ksort($data);
        $sign = hash_hmac('sha256', json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), $this->clientKey);

        $signData = json_encode(['client_id' => $this->clientId, 'sign' => $sign]);
        return base64_encode($signData);
    }
}