<?php

namespace Psamatt\Pecunia\Domain\Saving\PublicAPI\Command;

use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingId;

class DeleteSavingCommand implements \ServiceBus\ICommand
{
    public $savingId;

    public function __construct(SavingId $savingId)
    {
        $this->savingId = $savingId;
    }
}