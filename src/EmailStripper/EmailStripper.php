<?php
namespace EmailStripper;

use \InvalidArgumentException;

class EmailStripper
{
    /**
     *
     *
     * @param $messageBody
     * @param string|array $whatToStrip
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function strip($messageBody, $whatToStrip)
    {
        if (is_array($whatToStrip)) {
            foreach ($whatToStrip as $wts)
                $messageBody = self::strip($messageBody, $wts);

            return $messageBody;
        }

        $className = __NAMESPACE__ . '\\Stripper\\' . $whatToStrip;
        if (!class_exists($whatToStrip))
            throw new InvalidArgumentException("Unsupported strip '$whatToStrip'. Supported methods are in Stripper/");

        /** @var $stripper \EmailStripper\Stripper\StripperInterface */
        $stripper = new $className;
        return $stripper->setMessage($messageBody)
                        ->strip();
    }
}