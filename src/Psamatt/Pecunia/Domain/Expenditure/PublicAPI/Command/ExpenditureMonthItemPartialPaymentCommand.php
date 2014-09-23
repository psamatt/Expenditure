<?php

namespace Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\ExpenditureItemId;

use Money\Money;

class ExpenditureMonthItemPartialPaymentCommand implements \ServiceBus\ICommand
{
    public $accountHolderId;
    public $calendarPeriod;
    public $itemId;
    public $amount;

    public function __construct(AccountHolderId $accountHolderId, CalendarPeriod $calendarPeriod, ExpenditureItemId $itemId, Money $amount)
    {
        $this->accountHolderId = $accountHolderId;
        $this->calendarPeriod = $calendarPeriod;
        $this->itemId = $itemId;
        $this->amount = $amount;
    }
}