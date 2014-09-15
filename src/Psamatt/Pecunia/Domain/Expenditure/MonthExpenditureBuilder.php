<?php

namespace Psamatt\Pecunia\Domain\Expenditure;

use Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\Repository\IDefaultRecurringMonthExpenditureRepository;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\ExpenditureItemId;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Expenditure.MonthExpenditureBuilder")
 */
class MonthExpenditureBuilder
{
    private $defaultRecurringRepository;

    /**
     * @DI\InjectParams({
     *     "defaultRecurringRepository" = @DI\Inject("Pecunia.Expenditure.DefaultRecurringMonthExpenditure.repository")
     * })
     */
    public function __construct(
            IDefaultRecurringMonthExpenditureRepository $defaultRecurringRepository)
    {
        $this->defaultRecurringRepository = $defaultRecurringRepository;
    }

    /**
     * Build up a calendar month expenditure
     *
     * @param CalendarMonthExpenditure $calendarMonthExpenditure
     */
    public function build(CalendarMonthExpenditure $calendarMonthExpenditure)
    {
        $defaultRecurrings = $this->defaultRecurringRepository->findAllByAccountHolder($calendarMonthExpenditure->getAccountHolderId());

        foreach ($defaultRecurrings as $defaultRecurring) {
            $calendarMonthExpenditure->addItem(ExpenditureItemId::generate(), $defaultRecurring->getDescription(), $defaultRecurring->getAmount());
        }
    }
}