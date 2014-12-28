<?php
namespace EmailStripperTest\Stripper;

use EmailStripper\Stripper\QuotedReplies;

class QuotedRepliedTest extends \PHPUnit_Framework_TestCase
{
    public function testSetMessage()
    {
        $quotedReplies = new QuotedReplies();
        $quotedReplies->setMessage('test');

        $reflObject = new \ReflectionObject($quotedReplies);
        $messageProp = $reflObject->getProperty('_message');
        $messageProp->setAccessible(true);
        $message = $messageProp->getValue($quotedReplies);

        $this->assertEquals('test', $message);
    }

    /**
     * @dataProvider getMessage
     */
    public function testHasPositive($message)
    {
        $quotedReplies = new QuotedReplies();
        $quotedReplies->setMessage($message);
        $this->assertTrue($quotedReplies->has());
    }

    public function testHasNegative()
    {
        $message = 'THIS IS A TEST EMAIL';
        $quotedReplies = new QuotedReplies();
        $quotedReplies->setMessage($message);

        $this->assertFalse($quotedReplies->has());
    }

    /**
     * @dataProvider getMessage
     */
    public function testStripPositive($message)
    {
        $quotedReplies = new QuotedReplies();
        $quotedReplies->setMessage($message);

        $this->assertEquals('Real email text!', $quotedReplies->strip());
    }

    public function getMessage()
    {
        $message = <<<MSG
Real email text!

From: test@test.com [mailto:test@test.com]
Sent: Tuesday, January 13, 2009 1:27 PM

This is my email text!

----
2008/12/26 <test@test.com>

>  text
----
test@test.com wrote:
> text
----
      test@test.com wrote:         text
text
----
2009/1/13 <test@test.com>

>  text
----
 test@test.com wrote:         text
 text
----
2009/1/13 <test@test.com>

> text
> text
----
2009/1/13 <test@test.com>

> text
> text
----
test@test.com wrote:
> text
> text
<response here>
----
--- On Fri, 23/1/09, test@test.com <test@test.com> wrote:

> text
> text
MSG;

        return array(array($message));
    }
}