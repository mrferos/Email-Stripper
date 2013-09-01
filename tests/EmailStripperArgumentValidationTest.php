<?php

require_once 'PHPUnit/Framework/TestCase.php';
class EmailStripperArgumentValidationTest extends PHPUnit_Framework_TestCase {

    public function testMessageBodyAcceptArray()
    {
        $exception = null;
        try {
            \EmailStripper\EmailStripper::strip('test', ['QuotedReplies']);
        }catch(InvalidArgumentException $e) {
            $exception = $e;
        }

        $this->assertTrue(is_null($exception), "\$messageBody did NOT accept an array");
    }

    public function testMessageBodyAcceptString()
    {
        $exception = null;
        try {
            \EmailStripper\EmailStripper::strip('test', 'QuotedReplies');
        }catch(InvalidArgumentException $e) {
            $exception = $e;
        }

        $this->assertTrue(is_null($exception), "\$messageBody did NOT accept a string");
    }

    /**
     * @expectedException DomainException
     */
    public function testMessageBodyDenyInvalidStripper()
    {
        \EmailStripper\EmailStripper::strip('test', 'QuoteddReplies');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testStripperDenyInvalidArgType()
    {
        \EmailStripper\EmailStripper::strip('test', new stdClass());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMessageBodyDenyIvalidArgType()
    {
        \EmailStripper\EmailStripper::strip(new stdClass(), 'QuotedReplies');
    }
}