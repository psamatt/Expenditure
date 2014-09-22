<?php

namespace Psamatt\Pecunia\Domain\Expenditure\CommandHandler;

use Psamatt\Pecunia\Domain\Expenditure\Repository\IDefaultOneOffMonthExpenditureRepository;

use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("servicebus.command_handler")
 */
class RemoveDefaultOneOffMonthExpenditureCommandHandler implements \ServiceBus\ICommandHandler
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
        $defaultOneOffMonthExpenditure = $this->defaultOneOffMonthExpenditureRepository->find($command->defaultId);

        $this->defaultOneOffMonthExpenditureRepository->remove($defaultOneOffMonthExpenditure);
    }
}