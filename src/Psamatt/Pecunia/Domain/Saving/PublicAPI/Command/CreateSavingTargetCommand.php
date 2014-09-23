<?php

namespace Psamatt\Pecunia\Domain\Saving\PublicAPI\Command;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingTarget;

class CreateSavingTargetCommand implements \ServiceBus\ICommand
{
    public $description;
    public $target;
    public $accountHolderId;

    public function __construct($description, SavingTarget $target, AccountHolderId $accountHolderId)
    {
        $this->description = $description;
        $this->target = $target;
        $this->accountHolderId = $accountHolderId;
    }
}