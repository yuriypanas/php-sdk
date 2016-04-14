<?php

class MailfireUnsubTest extends PHPUnit_Framework_TestCase
{
    public function testShouldCheckAddByFbl()
    {
        $predefinedResult = array('data' => 'ok');
        $user = array('id' => 42);

        $request = $this->getMockBuilder('MailfireRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('create')
            ->with('unsub/' . $user['id'] . '/source/' . MailfireUnsub::SOURCE_FBL)
            ->will($this->returnValue($predefinedResult));

        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request = $request;
        $result = $mf->unsub->addByFbl($user);
        $this->assertEquals($predefinedResult, $result);
    }

    public function testShouldCheckAddByLink()
    {
        $predefinedResult = array('data' => 'ok');
        $user = array('id' => 42);

        $request = $this->getMockBuilder('MailfireRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('create')
            ->with('unsub/' . $user['id'] . '/source/' . MailfireUnsub::SOURCE_LINK)
            ->will($this->returnValue($predefinedResult));

        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request = $request;
        $result = $mf->unsub->addByLink($user);
        $this->assertEquals($predefinedResult, $result);
    }

    public function testShouldCheckAddByClient()
    {
        $predefinedResult = array('data' => 'ok');
        $user = array('id' => 42);

        $request = $this->getMockBuilder('MailfireRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('create')
            ->with('unsub/' . $user['id'] . '/source/' . MailfireUnsub::SOURCE_CLIENT)
            ->will($this->returnValue($predefinedResult));

        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request = $request;
        $result = $mf->unsub->addByClient($user);
        $this->assertEquals($predefinedResult, $result);
    }

    public function testShouldCheckAddBySettings()
    {
        $predefinedResult = array('data' => 'ok');
        $user = array('id' => 42);

        $request = $this->getMockBuilder('MailfireRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('create')
            ->with('unsub/' . $user['id'] . '/source/' . MailfireUnsub::SOURCE_SETTINGS)
            ->will($this->returnValue($predefinedResult));

        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request = $request;
        $result = $mf->unsub->addBySettings($user);
        $this->assertEquals($predefinedResult, $result);
    }
}
