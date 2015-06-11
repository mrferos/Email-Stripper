<?php
namespace EmailStripper\Stripper;

abstract class AbstractStripper implements StripperInterface
{
    /**
     * Contains the message to do work on
     *
     * @var string
     */
    protected $_message;

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        $this->_message = $message;
        return $this;
    }
}