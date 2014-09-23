<?php

namespace Psamatt\Pecunia\Domain\Saving\CommandHandler;

use Psamatt\Pecunia\Domain\Saving\Repository\ISavingRepository;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("servicebus.command_handler")
 */
class DeleteSavingCommandHandler implements \ServiceBus\ICommandHandler
{
    private $savingRepository;

    /**
     * @DI\InjectParams({
     *     "savingRepository" = @DI\Inject("Pecunia.Saving.Saving.repository")
     * })
     */
    public function __construct(
            ISavingRepository $savingRepository)
    {
        $this->savingRepository = $savingRepository;
    }

    public function handle(\ServiceBus\ICommand $command)
    {
        $saving = $this->savingRepository->find($command->savingId);
        $this->savingRepository->remove($saving);
    }
}