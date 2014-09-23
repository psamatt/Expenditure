<?php

namespace Psamatt\Pecunia\Domain\Expenditure;

use Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\Repository\IDefaultRecurringMonthExpenditureRepository;
use Psamatt\Pecunia\Domain\Expenditure\Repository\IDefaultOneOffMonthExpenditureRepository;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\ExpenditureItemId;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Expenditure.MonthExpenditureBuilder")
 */
class MonthExpenditureBuilder
{
    private $defaultRecurringRepository;
    private $defaultOneOffRepository;

    /**
     * @DI\InjectParams({
     *     "defaultRecurringRepository" = @DI\Inject("Pecunia.Expenditure.DefaultRecurringMonthExpenditure.repository"),
     *     "defaultOneOffRepository" = @DI\Inject("Pecunia.Expenditure.DefaultOneOffMonthExpenditure.repository")
     * })
     */
    public function __construct(
            IDefaultRecurringMonthExpenditureRepository $defaultRecurringRepository,
            IDefaultOneOffMonthExpenditureRepository $defaultOneOffRepository)
    {
        $this->defaultRecurringRepository = $defaultRecurringRepository;
        $this->defaultOneOffRepository = $defaultOneOffRepository;
    }

    /**
     * Build up a calendar month expenditure
     *
     * @param CalendarMonthExpenditure $calendarMonthExpenditure
     */
    public function build(CalendarMonthExpenditure $calendarMonthExpenditure)
    {
        /** recurring */
        $defaultRecurrings = $this->defaultRecurringRepository->findAllByAccountHolder($calendarMonthExpenditure->getAccountHolderId());

        foreach ($defaultRecurrings as $defaultRecurring) {
            $calendarMonthExpenditure->addItem(ExpenditureItemId::generate(), $defaultRecurring->getDescription(), $defaultRecurring->getAmount());
        }

        /** one offs */
        $oneOffs = $this->defaultOneOffRepository->findDueByAccountHolder($calendarMonthExpenditure->getAccountHolderId(), $calendarMonthExpenditure->getMonth());

        foreach ($oneOffs as $oneOff) {
            $calendarMonthExpenditure->addItem(ExpenditureItemId::generate(), $oneOff->getDescription(), $oneOff->getAmount());

            $this->defaultOneOffRepository->remove($oneOff);
        }
    }
}