<?php

namespace Psamatt\Pecunia\Query;

class AllSavingsQuery implements \ServiceBus\IQuery
{
    public $accountHolderId;

    public function __construct($accountHolderId)
    {
        $this->accountHolderId = $accountHolderId;
    }
}