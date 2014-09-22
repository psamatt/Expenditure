<?php

namespace Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\OneOffPaymentDueDate;

use Money\Money;

class NewDefaultOneOffMonthExpenditureCommand implements \ServiceBus\ICommand
{
    public $accountHolderId;
    public $description;
    public $amount;
    public $dueDate;

    public function __construct(AccountHolderId $accountHolderId, $description, Money $amount, OneOffPaymentDueDate $dueDate)
    {
        $this->accountHolderId = $accountHolderId;
        $this->description = $description;
        $this->amount = $amount;
        $this->dueDate = $dueDate;
    }
}