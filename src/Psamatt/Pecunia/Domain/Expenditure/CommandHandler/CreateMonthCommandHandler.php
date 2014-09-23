<?php

namespace Psamatt\Pecunia\Domain\Expenditure\CommandHandler;

use Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\Repository\ICalendarMonthExpenditureRepository;
use Psamatt\Pecunia\Domain\Expenditure\MonthExpenditureBuilder;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("servicebus.command_handler")
 */
class CreateMonthCommandHandler implements \ServiceBus\ICommandHandler
{
    private $monthExpenditureBuilder;
    private $calendarMonthExpenditureRepository;

    /**
     * @DI\InjectParams({
     *     "monthExpenditureBuilder" = @DI\Inject("Pecunia.Expenditure.MonthExpenditureBuilder"),
     *     "calendarMonthExpenditureRepository" = @DI\Inject("Pecunia.Expenditure.CalendarMonthExpenditure.repository")
     * })
     */
    public function __construct(
            MonthExpenditureBuilder $monthExpenditureBuilder,
            ICalendarMonthExpenditureRepository $calendarMonthExpenditureRepository)
    {
        $this->monthExpenditureBuilder = $monthExpenditureBuilder;
        $this->calendarMonthExpenditureRepository = $calendarMonthExpenditureRepository;
    }

    public function handle(\ServiceBus\ICommand $command)
    {
        $calendarMonthExpenditure = new CalendarMonthExpenditure($command->calendarPeriod, $command->accountHolderId, $command->money);

        $this->monthExpenditureBuilder->build($calendarMonthExpenditure);
        $this->calendarMonthExpenditureRepository->save($calendarMonthExpenditure);
    }
}