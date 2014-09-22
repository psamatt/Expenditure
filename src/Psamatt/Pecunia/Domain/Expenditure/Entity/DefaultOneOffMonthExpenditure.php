<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Entity;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;
use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\OneOffPaymentDueDate;

use Money\Money;

/**
 * Used to store all reoccuring monthly expenditure templates
 */
class DefaultOneOffMonthExpenditure extends DefaultMonthExpenditure
{
    private $dueDate;

    public function __construct(RecurringExpenditureId $id, AccountHolderId $accountHolderId, $description, Money $price, OneOffPaymentDueDate $dueDate)
    {
        parent::__construct($id, $accountHolderId, $description, $price);
        $this->dueDate = $dueDate;
    }

    public static function fqcn()
    {
        return __CLASS__;
    }

    /** @return \DateTime */
    public function getDueDate() { return $this->dueDate; }
}