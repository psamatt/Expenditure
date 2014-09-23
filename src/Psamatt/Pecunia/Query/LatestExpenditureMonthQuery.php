<?php

namespace Psamatt\Pecunia\Query;

class LatestExpenditureMonthQuery implements \ServiceBus\IQuery
{
    public $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }
}