<?php

namespace Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\ExpenditureItemId;

class ExpenditureMonthItemDeleteCommand implements \ServiceBus\ICommand
{
    public $accountHolderId;
    public $calendarPeriod;
    public $itemId;

    public function __construct(AccountHolderId $accountHolderId, CalendarPeriod $calendarPeriod, ExpenditureItemId $itemId)
    {
        $this->accountHolderId = $accountHolderId;
        $this->calendarPeriod = $calendarPeriod;
        $this->itemId = $itemId;
    }
}