<?php

namespace Psamatt\Pecunia\Query;

class SpecificExpenditureMonthQuery implements \ServiceBus\IQuery
{
    public $accountHolderId;
    public $year;
    public $month;

    public function __construct($accountHolderId, $year, $month)
    {
        $this->accountHolderId = $accountHolderId;
        $this->year = $year;
        $this->month = $month;
    }
}