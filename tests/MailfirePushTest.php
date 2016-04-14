<?php

class MailfirePushTest extends PHPUnit_Framework_TestCase
{
    public function testShouldCheckSend()
    {
        $predefinedResult = array('data' => 'ok');
        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';

        $data = array( // Data for letter
            'some' => 'hi',
            'letter' => 'John',
            'variables' => '!',
            'user' => array(
                'email' => 'someone@example.com'
            )
        );
        $meta = array(); // Your additional data

        $mf = new Mailfire($clientId, $clientKey);
        $mf->errorHandler->setErrorMode(MailfireErrorHandler::MODE_EXCEPTION);
        $params = array(
            'type_id' => 1,
            'category' => $mf->push->getCategorySystem(),
            'client_id' => $clientId,
            'project_id' => 3,
            'data' => $data,
            'meta' => $meta
        );

        $request = $this->getMockBuilder('MailfireRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('create')
            ->with('push/system', $params)
            ->will($this->returnValue($predefinedResult));

        $mf->request = $request;
        $result = $mf->push->send($params['type_id'], $params['category'], $params['project_id'], $data['user']['email'], array('email' => ''), $data, $meta);
        $this->assertEquals($predefinedResult, $result);
    }

    public function testShouldCheckGetCategorySystem()
    {
        $push = new MailfirePush($this);
        $this->assertEquals(MailfirePush::CATEGORY_SYSTEM, $push->getCategorySystem());
    }

    public function testShouldCheckGetCategoryTrigger()
    {
        $push = new MailfirePush($this);
        $this->assertEquals(MailfirePush::CATEGORY_TRIGGER, $push->getCategoryTrigger());
    }
}
