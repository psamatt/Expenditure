<?php

namespace Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;

class RemoveDefaultOneOffMonthExpenditureCommand implements \ServiceBus\ICommand
{
    public $accountHolderId;
    public $defaultId;

    public function __construct(AccountHolderId $accountHolderId, RecurringExpenditureId $defaultId)
    {
        $this->accountHolderId = $accountHolderId;
        $this->defaultId = $defaultId;
    }
}