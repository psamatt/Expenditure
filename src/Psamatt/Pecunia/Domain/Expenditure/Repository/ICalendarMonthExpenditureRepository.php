<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Repository;

use Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditure;
use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;

interface ICalendarMonthExpenditureRepository
{
    /**
     * Find a CalendarMonthExpenditure by the calenddr month and account holder id
     *
     * @param CalendarPeriod $calendarMonth
     * @param AccountHolderId $accountHolderId
     */
    public function findByDateAndAccountHolder(CalendarPeriod $calendarPeriod, AccountHolderId $accountHolderId);

    /**
     * Save
     *
     * @param CalendarMonthExpenditure $calendarMonthExpenditure
     */
    public function save(CalendarMonthExpenditure $calendarMonthExpenditure);
}