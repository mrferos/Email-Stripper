<?php
namespace EmailStripperTest;

use \EmailStripper\EmailStripper;

class EmailStripperTest extends \PHPUnit_Framework_TestCase {

    /**
     * @expectedException RuntimeException
     */
    public function testStripNoStrippersSet()
    {
        $emailStripper = new EmailStripper;
        $emailStripper->strip('test');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testStripNonStringMessage()
    {
        $stripper = $this->getMock('\EmailStripper\Stripper\StripperInterface');
        $emailStripper = new EmailStripper;
        $emailStripper->addStripper($stripper);
        $emailStripper->strip(array('test'));
    }

    public function testStripStringMessage()
    {
        $testString = 'TEST 123';
        $stripper = $this->getMock('\EmailStripper\Stripper\StripperInterface');
        $stripper->expects($this->any())->method('setMessage')->will($this->returnSelf());
        $stripper->expects($this->any())->method('strip')->will($this->returnValue($testString));

        $emailStripper = new EmailStripper;
        $emailStripper->addStripper($stripper);
        $message = $emailStripper->strip('test');

        $this->assertEquals($testString, $message);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddInvalidStripperStringThrowsException()
    {
        $emailStripper = new EmailStripper;
        $emailStripper->addStripper('Foo');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddInvalidStripperObjectThrowsException()
    {
        $emailStripper = new EmailStripper;
        $emailStripper->addStripper(new \stdClass());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddInvalidStringStripperWhichIsValidClassThrowsException()
    {
        $emailStripper = new EmailStripper;
        $emailStripper->addStripper('stdClass');
    }

    public function testAddStringStripperConvertsToObject()
    {
        $emailStripper = new EmailStripper;
        $emailStripper->addStripper('QuotedReplies');

        $strippers = $emailStripper->getStrippers();
        $this->assertInternalType('object', $strippers[0]);
    }

    public function testGetStrippers()
    {
        $emailStripper = new EmailStripper;
        $this->assertInternalType('array', $emailStripper->getStrippers());
    }


}