<?php
namespace EmailStripper\Stripper;

use \RuntimeException;

class QuotedReplies implements StripperInterface
{
    protected $_message;
    protected $_regex;

    public function __construct()
    {
        /** general spacers for time and date */
        $spacers = "[\\s,/\\.\\-]";

        /** matches times */
        $timePattern  = "(?:[0-2])?[0-9]:[0-5][0-9](?::[0-5][0-9])?(?:(?:\\s)?[AP]M)?";

        /** matches day of the week */
        $dayPattern   = "(?:(?:Mon(?:day)?)|(?:Tue(?:sday)?)|(?:Wed(?:nesday)?)|(?:Thu(?:rsday)?)|(?:Fri(?:day)?)|(?:Sat(?:urday)?)|(?:Sun(?:day)?))";

        /** matches day of the month (number and st, nd, rd, th) */
        $dayOfMonthPattern = "[0-3]?[0-9]" . $spacers . "*(?:(?:th)|(?:st)|(?:nd)|(?:rd))?";

        /** matches months (numeric and text) */
        $monthPattern = "(?:(?:Jan(?:uary)?)|(?:Feb(?:uary)?)|(?:Mar(?:ch)?)|(?:Apr(?:il)?)|(?:May)|(?:Jun(?:e)?)|(?:Jul(?:y)?)" .
            "|(?:Aug(?:ust)?)|(?:Sep(?:tember)?)|(?:Oct(?:ober)?)|(?:Nov(?:ember)?)|(?:Dec(?:ember)?)|(?:[0-1]?[0-9]))";

        /** matches years (only 1000's and 2000's, because we are matching emails) */
        $yearPattern  = "(?:[1-2]?[0-9])[0-9][0-9]";

        /** matches a full date */
        $datePattern     = "(?:" . $dayPattern . $spacers . "+)?(?:(?:" . $dayOfMonthPattern . $spacers . "+" . $monthPattern . ")|" .
            "(?:" . $monthPattern . $spacers . "+" . $dayOfMonthPattern . "))" .
            $spacers . "+" . $yearPattern;

        /** matches a date and time combo (in either order) */
        $dateTimePattern = "(?:" . $datePattern . "[\\s,]*(?:(?:at)|(?:@))?\\s*" . $timePattern . ")|" .
            "(?:" . $timePattern . "[\\s,]*(?:on)?\\s*". $datePattern . ")";

        /** matches a leading line such as
         * ----Original Message----
         * or simply
         * ------------------------
         */
        $leadInLine    = "-+\\s*(?:Original(?:\\sMessage)?)?\\s*-+\\n";

        /** matches a header line indicating the date */
        $dateLine    = "(?:(?:date)|(?:sent)|(?:time)):\\s*". $dateTimePattern . ".*\\n";

        /** matches a subject or address line */
        $subjectOrAddressLine    = "((?:from)|(?:subject)|(?:b?cc)|(?:to))|:.*\\n";

        /** matches gmail style quoted text beginning, i.e.
         * On Mon Jun 7, 2010 at 8:50 PM, Simon wrote:
         */
        $gmailQuotedTextBeginning = "(On\\s+" . $dateTimePattern . ".*wrote:\\n)";


        /** matches the start of a quoted section of an email */
        $this->_regex = "~(?i)(?:(?:" . $leadInLine . ")?" .
            "(?:(?:" . $subjectOrAddressLine . ")|(?:" . $dateLine . ")){2,6})|(?:" .
            $gmailQuotedTextBeginning . ")~";
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->_message = $message;
    }

    /**
     * Check if the message actually contains
     * what we're trying to strip.
     *
     * @return bool
     * @throws RuntimeException
     */
    public function has()
    {
        if (empty($this->_message))
            throw new RuntimeException("A message has not been set");

        return preg_match($this->_regex, $this->_message);
    }

    /**
     * Remove unwanted section
     *
     * @return string
     * @throws RuntimeException
     */
    public function strip()
    {
        if (empty($this->_message))
            throw new RuntimeException("A message has not been set");

        $messageBody = $this->_message;
        if (preg_match_all($this->_regex, $messageBody, $matches)) {
            foreach ($matches[0] as $k => $header) {
                $startPos = strpos($messageBody, $header);
                $lookAhead = $k+1;
                $afterPos = array_key_exists($lookAhead, $matches[0]) ?
                    strpos($messageBody, $matches[0][$lookAhead]) : strlen($messageBody);

                $messageBody = substr($messageBody, 0, $startPos) . " " . substr($messageBody, $afterPos);
            }
        }

        return trim($messageBody);
    }

}