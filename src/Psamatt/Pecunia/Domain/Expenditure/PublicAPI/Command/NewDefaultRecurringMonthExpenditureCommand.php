<?php

namespace Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;

use Money\Money;

class NewDefaultRecurringMonthExpenditureCommand implements \ServiceBus\ICommand
{
    public $accountHolderId;
    public $description;
    public $amount;

    public function __construct(AccountHolderId $accountHolderId, $description, Money $amount)
    {
        $this->accountHolderId = $accountHolderId;
        $this->description = $description;
        $this->amount = $amount;
    }
}