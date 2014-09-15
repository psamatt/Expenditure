<?php

namespace Psamatt\Pecunia\Domain\Saving\ValueObject;

use Money\Money;
use DateTime;

class SavingTarget
{
    public function __construct(DateTime $targetDate, Money $targetAmount)
    {
        if ($targetDate < new DateTime) {
            throw new \InvalidArgumentException('Saving target cannot be in the past');
        }

        $this->targetDate = $targetDate;
        $this->targetAmount = $targetAmount;
    }
    
    public function getTargetDate()
    {
        return $this->targetDate;
    }
    
    public function getTargetAmount()
    {
        return $this->targetAmount;
    }
}