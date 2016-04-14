<?php

class MailfireDiTest extends PHPUnit_Framework_TestCase
{
    public function testShouldCheckThatDiReturnsCorrectProperties()
    {
        $this->testProperty = array(array('test_property'));
        $di = new MailfireDi($this);
        $this->assertSame($this->testProperty, $di->testProperty);
        $this->testProperty = array(array('new test data'));
        $this->assertSame($this->testProperty, $di->testProperty);
    }
}
