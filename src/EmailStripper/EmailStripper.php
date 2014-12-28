<?php
namespace EmailStripper;

use EmailStripper\Stripper\StripperInterface;
use \InvalidArgumentException;
use \RuntimeException;

class EmailStripper
{
    /**
     * @var StripperInterface[]
     */
    protected $_stippers = array();

    /**
     * @param string $message
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return string
     */
    public function strip($message)
    {
        if (!is_string($message)) {
            throw new InvalidArgumentException('$message MUST be a string');
        }

        if (empty($this->_stippers)) {
            throw new RuntimeException('No strippers have been added!');
        }

        foreach ($this->_stippers as $stripper) {
            $message = $stripper->setMessage($message)->strip();
        }

        return $message;
    }

    /**
     * Accept an object or class name that implements StripperInterface
     *
     * @param string|StripperInterface $stripper
     */
    public function addStripper($stripper)
    {
        if (is_string($stripper)) {
            if (!class_exists($stripper)) {
                $stripper = '\\' . __NAMESPACE__ . '\\Stripper\\' . $stripper;
                if (!class_exists($stripper)) {
                    throw new \InvalidArgumentException('Class ' . $stripper . ' can not be found');
                }
            }

            $stripper = new $stripper;
            if (!$stripper instanceof StripperInterface) {
                throw new InvalidArgumentException('Class "' . get_class($stripper) . '" must implement StripperInterface');
            }

        } elseif (!$stripper instanceof StripperInterface) {
            throw new InvalidArgumentException('Class "' . get_class($stripper) . '" must implement StripperInterface');
        }

        $this->_stippers[] = $stripper;
    }

    /**
     * @return Stripper\StripperInterface[]
     */
    public function getStrippers()
    {
        return $this->_stippers;
    }
}