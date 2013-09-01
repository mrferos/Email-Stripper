<?php
namespace EmailStripper\Stripper;

interface StripperInterface
{
    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);
    /**
     * Check if the message actually contains
     * what we're trying to strip.
     *
     * @return bool
     */
    public function has();

    /**
     * Remove unwanted section
     *
     * @return string
     */
    public function strip();
}