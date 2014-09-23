<?php

namespace Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;
use Money\Money;

class CreateMonthCommand implements \ServiceBus\ICommand
{
    public $accountHolderId;
    public $calendarPeriod;
    public $money;

    public function __construct(AccountHolderId $accountHolderId, CalendarPeriod $calendarPeriod, Money $money)
    {
        $this->accountHolderId = $accountHolderId;
        $this->calendarPeriod = $calendarPeriod;
        $this->money = $money;
    }
}