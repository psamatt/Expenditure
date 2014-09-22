<?php

namespace Psamatt\Pecunia\Domain\Expenditure\CommandHandler;

use Psamatt\Pecunia\Domain\Expenditure\Repository\IDefaultOneOffMonthExpenditureRepository;
use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultOneOffMonthExpenditure;

use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("servicebus.command_handler")
 */
class NewDefaultOneOffMonthExpenditureCommandHandler implements \ServiceBus\ICommandHandler
{
    private $defaultOneOffMonthExpenditureRepository;

    /**
     * @DI\InjectParams({
     *     "defaultOneOffMonthExpenditureRepository" = @DI\Inject("Pecunia.Expenditure.DefaultOneOffMonthExpenditure.repository")
     * })
     */
    public function __construct(
            IDefaultOneOffMonthExpenditureRepository $defaultOneOffMonthExpenditureRepository)
    {
        $this->defaultOneOffMonthExpenditureRepository = $defaultOneOffMonthExpenditureRepository;
    }

    public function handle(\ServiceBus\ICommand $command)
    {
        $this->defaultOneOffMonthExpenditureRepository->save(new DefaultOneOffMonthExpenditure(RecurringExpenditureId::generate(), $command->accountHolderId, $command->description, $command->amount, $command->dueDate));
    }
}