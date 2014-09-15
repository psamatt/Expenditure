<?php

namespace Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\ExpenditureItemId;

use Money\Money;

class ExpenditureMonthItemNewCommand implements \ServiceBus\ICommand
{
    public $accountHolderId;
    public $calendarPeriod;
    public $description;
    public $amount;

    public function __construct(AccountHolderId $accountHolderId, CalendarPeriod $calendarPeriod, $description, Money $amount)
    {
        $this->accountHolderId = $accountHolderId;
        $this->calendarPeriod = $calendarPeriod;
        $this->description = $description;
        $this->amount = $amount;
    }
}