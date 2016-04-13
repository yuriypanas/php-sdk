<?php
class MailfireEmailTest extends PHPUnit_Framework_TestCase
{

    public function testShouldCheckActionCheck()
    {
        $email = 'someone@example.com';
        $predefinedResult = array('data' => 'ok');

        $request = $this->getMockBuilder('MailfireRequest')
            ->disableOriginalConstructor()
            ->getMock();
        $request->expects($this->once())
            ->method('create')
            ->with(MailfireEmail::CHECK_EMAIL_RESOURCE, array('email' => $email))
            ->will($this->returnValue($predefinedResult));

        $clientId = 123;
        $clientKey = 'a1s2d3f4g5h6j7k8l';
        $mf = new Mailfire($clientId, $clientKey, true);
        $mf->request = $request;
        $result = $mf->email->check($email);
        $this->assertEquals($predefinedResult, $result);
    }
}
