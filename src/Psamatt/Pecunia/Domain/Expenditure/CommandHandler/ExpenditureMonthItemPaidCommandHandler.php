<?php

namespace Psamatt\Pecunia\Domain\Expenditure\CommandHandler;

use Psamatt\Pecunia\Domain\Expenditure\Repository\ICalendarMonthExpenditureRepository;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("servicebus.command_handler")
 */
class ExpenditureMonthItemPaidCommandHandler implements \ServiceBus\ICommandHandler
{
    private $calendarMonthExpenditureRepository;

    /**
     * @DI\InjectParams({
     *     "calendarMonthExpenditureRepository" = @DI\Inject("Pecunia.Expenditure.CalendarMonthExpenditure.repository")
     * })
     */
    public function __construct(ICalendarMonthExpenditureRepository $calendarMonthExpenditureRepository)
    {
        $this->calendarMonthExpenditureRepository = $calendarMonthExpenditureRepository;
    }

    public function handle(\ServiceBus\ICommand $command)
    {
        $calendarMonthExpenditure = $this->calendarMonthExpenditureRepository
                                        ->findByDateAndAccountHolder($command->calendarPeriod, $command->accountHolderId);
        $calendarMonthExpenditure->markAsPaid($command->itemId);

        $this->calendarMonthExpenditureRepository->save($calendarMonthExpenditure);
    }
}