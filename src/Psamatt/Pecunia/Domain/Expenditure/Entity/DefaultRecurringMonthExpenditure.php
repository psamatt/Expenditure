<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Entity;

use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultMonthExpenditure;

/**
 * Used to store all reoccuring monthly expenditure templates
 */
class DefaultRecurringMonthExpenditure extends DefaultMonthExpenditure
{
    public static function fqcn()
    {
        return __CLASS__;
    }
}