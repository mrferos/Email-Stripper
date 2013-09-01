<?php
namespace EmailStripper;

use \InvalidArgumentException;
use \DomainException;

class EmailStripper
{
    /**
     * Take an email message body and strip desired contents
     * from it
     *
     * @param string $messageBody
     * @param string|array $whatToStrip
     * @return string
     * @throws InvalidArgumentException
     * @throws DomainException
     */
    public static function strip($messageBody, $whatToStrip)
    {
        if (!is_string($messageBody))
            throw new InvalidArgumentException("\$messageBody MUST be a string");

        if (!is_string($whatToStrip) && !is_array($whatToStrip))
            throw new InvalidArgumentException("Only strings and arrays are supported in \$whatToStrip");

        if (is_array($whatToStrip)) {
            foreach ($whatToStrip as $wts)
                $messageBody = self::strip($messageBody, $wts);

            return $messageBody;
        }

        $className = '\\' . __NAMESPACE__ . '\\Stripper\\' . $whatToStrip;
        if (!class_exists($className))
            throw new DomainException("Unsupported strip '$whatToStrip'. Supported methods are in Stripper/");

        /** @var $stripper \EmailStripper\Stripper\StripperInterface */
        $stripper = new $className;
        return $stripper->setMessage($messageBody)
                        ->strip();
    }
}