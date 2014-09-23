<?php

namespace Psamatt\Pecunia\Query\Handler;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 * @DI\Tag("servicebus.query_handler")
 */
class AllDefaultPaymentQueryHandler implements \ServiceBus\IQueryHandler
{
    private $defaultPaymentRepository;

    /**
     * @DI\InjectParams({
     *     "defaultPaymentRepository" = @DI\Inject("Pecunia.Query.DefaultRecurringMonthExpenditure.repository")
     * })
     */
    public function __construct(
        \Psamatt\Pecunia\Query\Repository\IDefaultRecurringMonthExpenditureRepository $defaultPaymentRepository)
    {
        $this->defaultPaymentRepository = $defaultPaymentRepository;
    }

    public function handle(\ServiceBus\IQuery $query)
    {
        return $this->defaultPaymentRepository->findAll($query->accountHolderId);
    }
}