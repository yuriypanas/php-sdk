<?php
class MailfireTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage clientId cannot be empty
     */
    public function testShouldCheckClientIdEmptyValidation()
    {
        new Mailfire('','', true);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage clientKey cannot be empty
     */
    public function testShouldCheckClientKeyEmptyValidation()
    {
        new Mailfire('1', '', true);
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage clientKey must be a string
     */
    public function testShouldCheckClientKeyStringValidation()
    {
        new Mailfire('1', array('lk3kljhow43'), true);
    }

    public function testShouldCheckPropertyTypes()
    {
        $mf = new Mailfire(123, 'some key');
        $this->assertInstanceOf('MailfireRequest', $mf->request);
        $this->assertInstanceOf('MailfirePush', $mf->push);
        $this->assertInstanceOf('MailfireEmail', $mf->email);
        $this->assertInstanceOf('MailfireUser', $mf->user);
        $this->assertInstanceOf('MailfireUnsub', $mf->unsub);
    }
}
