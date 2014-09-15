<?php

namespace Psamatt\Pecunia\Domain\Expenditure\CommandHandler;

use Psamatt\Pecunia\Domain\Expenditure\Repository\IDefaultRecurringMonthExpenditureRepository;
use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultRecurringMonthExpenditure;

use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("servicebus.command_handler")
 */
class NewDefaultRecurringMonthExpenditureCommandHandler implements \ServiceBus\ICommandHandler
{
    private $defaultRecurringMonthExpenditureRepository;

    /**
     * @DI\InjectParams({
     *     "defaultRecurringMonthExpenditureRepository" = @DI\Inject("Pecunia.Expenditure.DefaultRecurringMonthExpenditure.repository")
     * })
     */
    public function __construct(
            IDefaultRecurringMonthExpenditureRepository $defaultRecurringMonthExpenditureRepository)
    {
        $this->defaultRecurringMonthExpenditureRepository = $defaultRecurringMonthExpenditureRepository;
    }

    public function handle(\ServiceBus\ICommand $command)
    {
        $this->defaultRecurringMonthExpenditureRepository->save(new DefaultRecurringMonthExpenditure(RecurringExpenditureId::generate(), $command->accountHolderId, $command->description, $command->amount));
    }
}