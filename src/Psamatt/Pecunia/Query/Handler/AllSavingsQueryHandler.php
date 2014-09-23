<?php

namespace Psamatt\Pecunia\Query\Handler;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("servicebus.query_handler")
 */
class AllSavingsQueryHandler implements \ServiceBus\IQueryHandler
{
    private $savingRepository;

    /**
     * @DI\InjectParams({
     *     "savingRepository" = @DI\Inject("Pecunia.Query.Saving.repository")
     * })
     */
    public function __construct(
        \Psamatt\Pecunia\Query\Repository\ISavingRepository $savingRepository)
    {
        $this->savingRepository = $savingRepository;
    }

    public function handle(\ServiceBus\IQuery $query)
    {
        return $this->savingRepository->findAll($query->accountHolderId);
    }
}