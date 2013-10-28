<?php

namespace Psamatt\ExpenditureBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class MessageEvent extends Event
{
    protected $msg;

    public function __construct($msg)
    {
        $this->msg = $msg;
    }

    public function getMessage()
    {
        return $this->msg;
    }
}