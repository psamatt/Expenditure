<?php

namespace Psamatt\Pecunia\Domain\Saving\PublicAPI\Command;

use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingId;
use Money\Money;

class DepositSavingMoneyCommand implements \ServiceBus\ICommand
{
    public $savingId;
    public $amount;

    public function __construct(SavingId $savingId, Money $amount)
    {
        $this->savingId = $savingId;
        $this->amount = $amount;
    }
}