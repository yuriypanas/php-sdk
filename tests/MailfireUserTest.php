<?php

class MailfireUserTest extends PHPUnit_Framework_TestCase
{
    public function testShouldCheckGetByEmail()
    {
        $predefinedResult = array('user' => array('id' => 42));
        $projectId = 3;
        $email = 'someone@example.com';

        $request = $this->getMockBuilder('MailfireRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('receive')
            ->with('user/project/' . $projectId . '/email/' . $email)
            ->will($this->returnValue($predefinedResult));

        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request = $request;
        $result = $mf->user->getByEmail($email, $projectId);
        $this->assertEquals($predefinedResult['user'], $result);
    }

    public function testShouldCheckGetById()
    {
        $predefinedResult = array('user' => array('id' => 42));
        $userId = 42;

        $request = $this->getMockBuilder('MailfireRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('receive')
            ->with('user/id/' . $userId)
            ->will($this->returnValue($predefinedResult));

        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request = $request;
        $result = $mf->user->getById($userId);
        $this->assertEquals($predefinedResult['user'], $result);
    }

    public function testShouldCheckResolveId()
    {
        $predefinedResult = array('user' => array('id' => 42));
        $userId = 42;

        $request = $this->getMockBuilder('MailfireRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('receive')
            ->with('user/id/' . $userId)
            ->will($this->returnValue($predefinedResult));

        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $mf->request = $request;
        $result = $mf->user->resolve($userId);
        $this->assertEquals($predefinedResult['user'], $result);
    }

    public function testShouldCheckResolveArray()
    {
        $predefinedUser = array(
            'id' => 42,
            'name' => 'John'
        );
        $user = new MailfireUser($this);
        $this->assertEquals($predefinedUser, $user->resolve($predefinedUser));

    }

    public function testShouldCheckPlatforms()
    {
        $user = new MailfireUser($this);
        $this->assertEquals(MailfireUser::PLATFORM_ANDROID, $user->getPlatformAndroid());
        $this->assertEquals(MailfireUser::PLATFORM_IOS, $user->getPlatformIos());
        $this->assertEquals(MailfireUser::PLATFORM_DESKTOP, $user->getPlatformDesktop());
        $this->assertEquals(MailfireUser::PLATFORM_MOBILE, $user->getPlatformMobile());
        $this->assertEquals(MailfireUser::PLATFORM_UNKNOWN, $user->getPlatformUnknown());
    }
}
