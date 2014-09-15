<?php

namespace Psamatt\Pecunia\Query\Handler;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service()
 * @DI\Tag("servicebus.query_handler")
 */
class AllExpenditureMonthOverviewQueryHandler implements \ServiceBus\IQueryHandler
{
    private $expenditureMonthRepository;

    /**
     * @DI\InjectParams({
     *     "expenditureMonthRepository" = @DI\Inject("Pecunia.Query.ExpenditureMonth.repository")
     * })
     */
    public function __construct(
        \Psamatt\Pecunia\Query\Repository\IExpenditureMonthRepository $expenditureMonthRepository)
    {
        $this->expenditureMonthRepository = $expenditureMonthRepository;
    }

    public function handle(\ServiceBus\IQuery $query)
    {
        return $this->expenditureMonthRepository->findHistoricOverview($query->accountHolderId);
    }
}