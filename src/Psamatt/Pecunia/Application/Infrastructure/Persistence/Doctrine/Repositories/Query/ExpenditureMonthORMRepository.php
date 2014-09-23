<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Query;

use Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditure;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Query.ExpenditureMonth.repository")
 */
class ExpenditureMonthORMRepository implements \Psamatt\Pecunia\Query\Repository\IExpenditureMonthRepository
{
    private $em;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /** {inheritdoc} */
    public function findLatestByUser($accountHolderId)
    {
        $query = $this->em->createQuery('SELECT cme
                FROM ' . CalendarMonthExpenditure::fqcn() . ' cme
                WHERE cme.accountHolderId = :accountHolderId
                    ORDER BY cme.calendarDate DESC');

        $query->setMaxResults(1);
        $query->setParameters(['accountHolderId' => $accountHolderId]);

        return $query->getOneOrNullResult();
    }

    /** {inheritdoc} */
    public function findByMonth($accountHolderId, \DateTime $monthYear)
    {
        $query = $this->em->createQuery('SELECT cme
                FROM ' . CalendarMonthExpenditure::fqcn() . ' cme
                WHERE
                    cme.accountHolderId = :accountHolderId
                    AND cme.calendarDate = :calendarMonth');

        $query->setParameters([
                'accountHolderId' => $accountHolderId,
                'calendarMonth' => $monthYear,
            ]);

        $result = $query->getOneOrNullResult();

        if (null === $result) {
            throw new \InvalidArgumentException('No expenditure found');
        }

        return $result;
    }

    /** { inheritdoc} */
    public function findHistoricOverview($accountHolderId)
    {
        $query = $this->em->createQuery('SELECT cme
                FROM ' . CalendarMonthExpenditure::fqcn() . ' cme
                WHERE cme.accountHolderId = :accountHolderId');

        $query->setParameters(['accountHolderId' => $accountHolderId]);

        return $query->getResult();
    }
}