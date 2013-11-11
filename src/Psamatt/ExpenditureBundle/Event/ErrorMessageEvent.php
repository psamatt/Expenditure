<?php

namespace Psamatt\ExpenditureBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ErrorMessageEvent extends Event implements MessageEventInterface
{
    protected $msg;
    protected $type;

    public function __construct($msg, $type = null)
    {
        $this->msg = $msg;
        $this->type = $type == null? 'errors': $type;
    }

    /**
     * {inheritdoc}
     */
    public function getMessage()
    {
        return $this->msg;
    }
    
    /**
     * {inheritdoc}
     */
    public function getMessageType()
    {
        return $this->type;
    }
}