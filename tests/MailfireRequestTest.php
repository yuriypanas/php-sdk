<?php

class MailfireRequestTest extends PHPUnit_Framework_TestCase
{
    public function testShouldCheckSuccessReceive()
    {
        $responseData = 'response data';
        $curl = $this->getMock('MailfireCurlRequest');
        $curl->expects($this->any())
            ->method('execute')
            ->will($this->returnValue(json_encode(array('data' => $responseData))));
        $curl->expects($this->any())
            ->method('getInfo')
            ->will($this->returnValue(200));

        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request->setCurlRequest($curl);
        $data = $mf->request->receive('test/get');
        $this->assertEquals($responseData, $data);
    }

    /**
     * @expectedException Exception
     */
    public function testShouldCheck404Receive()
    {
        $curl = $this->getMock('MailfireCurlRequest');
        $curl->expects($this->any())
            ->method('getInfo')
            ->will($this->returnValue(404));
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request->setCurlRequest($curl);
        $data = $mf->request->receive('test/get');
        $this->assertFalse($data);
    }

    public function testShouldCheckEmptyDataReceive()
    {
        $curl = $this->getMock('MailfireCurlRequest');
        $curl->expects($this->any())
            ->method('execute')
            ->will($this->returnValue(json_encode(array('OK'))));
        $curl->expects($this->any())
            ->method('getInfo')
            ->will($this->returnValue(200));
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request->setCurlRequest($curl);
        $data = $mf->request->receive('test/get');
        $this->assertTrue($data);
    }

    public function testShouldCheckSuccessCreate()
    {
        $curl = $this->getMock('MailfireCurlRequest');
        $curl->expects($this->any())
            ->method('execute')
            ->will($this->returnValue(json_encode(array('OK'))));
        $curl->expects($this->any())
            ->method('getInfo')
            ->will($this->returnValue(200));
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $data = array(
            'name' => 'John',
            'age' => 23,
            'type' => null,
            'array_data' => array(
                'key' => 'value'
            )
        );
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request->setCurlRequest($curl);
        $response = $mf->request->create('test', $data);
        $this->assertTrue($response);
    }

    /**
     * @expectedException Exception
     */
    public function testShouldCheckFailedCreate()
    {
        $curl = $this->getMock('MailfireCurlRequest');
        $curl->expects($this->any())
            ->method('execute')
            ->will($this->returnValue(json_encode(array('Error'))));
        $curl->expects($this->any())
            ->method('getInfo')
            ->will($this->returnValue(401));
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $data = array(
            'name' => 'John',
            'age' => 23,
            'type' => null,
            'array_data' => array(
                'key' => 'value'
            )
        );
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request->setCurlRequest($curl);
        $response = $mf->request->create('test', $data);
        $this->assertFalse($response);
    }

    public function testShouldCheckSuccessUpdate()
    {
        $curl = $this->getMock('MailfireCurlRequest');
        $curl->expects($this->any())
            ->method('execute')
            ->will($this->returnValue(json_encode(array('OK'))));
        $curl->expects($this->any())
            ->method('getInfo')
            ->will($this->returnValue(200));
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $data = array(
            'name' => 'John',
            'age' => 23,
            'type' => null,
            'array_data' => array(
                'key' => 'value'
            )
        );
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request->setCurlRequest($curl);
        $response = $mf->request->update('test/42', $data);
        $this->assertTrue($response);
    }

    /**
     * @expectedException Exception
     */
    public function testShouldCheckFailedUpdate()
    {
        $curl = $this->getMock('MailfireCurlRequest');
        $curl->expects($this->any())
            ->method('execute')
            ->will($this->returnValue(json_encode(array('Error'))));
        $curl->expects($this->any())
            ->method('getInfo')
            ->will($this->returnValue(403));
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $data = array(
            'name' => 'John',
            'age' => 23,
            'type' => null,
            'array_data' => array(
                'key' => 'value'
            )
        );
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request->setCurlRequest($curl);
        $response = $mf->request->update('test/42', $data);
        $this->assertFalse($response);
    }

    public function testShouldCheckSuccessDelete()
    {
        $curl = $this->getMock('MailfireCurlRequest');
        $curl->expects($this->any())
            ->method('execute')
            ->will($this->returnValue(json_encode(array('OK'))));
        $curl->expects($this->any())
            ->method('getInfo')
            ->will($this->returnValue(200));
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';

        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request->setCurlRequest($curl);
        $response = $mf->request->delete('test/42');
        $this->assertTrue($response);
    }

    /**
     * @expectedException Exception
     */
    public function testShouldCheckFailedDelete()
    {
        $curl = $this->getMock('MailfireCurlRequest');
        $curl->expects($this->any())
            ->method('execute')
            ->will($this->returnValue(json_encode(array('Error'))));
        $curl->expects($this->any())
            ->method('getInfo')
            ->will($this->returnValue(500));
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';

        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request->setCurlRequest($curl);
        $response = $mf->request->delete('test/42');
        $this->assertFalse($response);
    }

    public function testShouldCheckGettingSign()
    {
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $sign = $this->invokeMethod($mf->request, 'getSign', array('http://www.example.com/test.html', 'GET', array()));
        $this->assertEquals('eyJjbGllbnRfaWQiOjEyMywic2lnbiI6ImNlM2ZjYWI2NjQxOGZkZjNjNjAxMGRkYTc0YWRjMGFmM2E1NTg1ZDQxOTExNDBlZjZjMjk5MjY0MmUzMDNlN2IifQ==', $sign);

        $data = array(
            'id' => 1,
            'age' => null,
            'projects' => array(1, 2, 3),
            'name' => 'Ondřej Surý'
        );
        $sign = $this->invokeMethod($mf->request, 'getSign', array('http://www.example.com/test.html', 'POST', $data));
        $this->assertEquals('eyJjbGllbnRfaWQiOjEyMywic2lnbiI6ImZmMTgwZmU0OTFhMjEyNWYyYTVhZGY2MWY5MTA4MjI1YmJjNzYwMGM3NWJlMjc1MDI5YWZhNTVhYjE0MjI5ZmYifQ==', $sign);

        $sign = $this->invokeMethod($mf->request, 'getSign', array('http://www.example.com/test.html', 'PUT', $data));
        $this->assertEquals('eyJjbGllbnRfaWQiOjEyMywic2lnbiI6IjYyZmY2ZTY3MmRhMjFhNzZlNjdkOTBiMWZmMmE0ODc3ZDlhZThhYzZhMWUwOGZmNzUxYmI5MzI2ZDg1YzhiMzQifQ==', $sign);

        $sign = $this->invokeMethod($mf->request, 'getSign', array('http://www.example.com/item/12', 'DELETE', array()));
        $this->assertEquals('eyJjbGllbnRfaWQiOjEyMywic2lnbiI6IjFhZTQwYzlmNGJmNjI4YzE5N2I5M2I5YzgzZDkxYTk2Mzc0ZjFjOTBlY2FjZmVmYzA3NzYxZjk5ZmZmYTEyOGIifQ==', $sign);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
