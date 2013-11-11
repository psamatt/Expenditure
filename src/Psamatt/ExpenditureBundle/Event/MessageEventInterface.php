<?php

namespace Psamatt\ExpenditureBundle\Event;

/**
 * An interface that all message events should implement
 *
 */
interface MessageEventInterface
{
    /**
     * Constructor
     *
     */
    public function __construct($msg, $type = null);
    
    /**
     * Get the message
     *
     * @return string 
     */
    public function getMessage();
    
    /**
     * Get the message type
     * 
     * @return string the message type
     */
    public function getMessageType();
}