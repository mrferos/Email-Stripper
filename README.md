Email-Stripper
==============

A library to strip things from email, right now it's just quoted replies. Hoping to reliably get signatures soon.

###Code Examples
```php
$emailStripper = new \EmailStripper\EmailStripper;
$emailStripper->addStripper('QuotedReplies');
$strippedMessage = $emailStripper->strip($message);
```

###Adding your own stripper

Your need to write a class that implements the StripperInterface which contains three methods:

* setMessage
* has
* strip

Afterwards you can call addStripper with either an object or classpath.
```php
$emailStripper = new \EmailStripper\EmailStripper;
$emailStripper->addStripper('My\Customer\Stripper');
```

That's it!

