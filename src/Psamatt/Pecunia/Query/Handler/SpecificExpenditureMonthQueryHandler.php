<?php

namespace Psamatt\Pecunia\Query\Handler;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service()
 * @DI\Tag("servicebus.query_handler")
 */
class SpecificExpenditureMonthQueryHandler implements \ServiceBus\IQueryHandler
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
        return $this->expenditureMonthRepository->findByMonth($query->accountHolderId, new \DateTime($query->year.'-'.$query->month.'-01'));
    }
}