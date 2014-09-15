<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Query;

use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultRecurringMonthExpenditure;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Query.DefaultRecurringMonthExpenditure.repository")
 */
class DefaultRecurringMonthExpenditureORMRepository implements \Psamatt\Pecunia\Query\Repository\IDefaultRecurringMonthExpenditureRepository
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
    public function findAll($accountHolderId)
    {
        $query = $this->em->createQuery('SELECT drme
                FROM ' . DefaultRecurringMonthExpenditure::fqcn() . ' drme
                WHERE drme.accountHolderId = :accountHolderId
                    ORDER BY drme.price DESC');

        $query->setParameters(['accountHolderId' => $accountHolderId]);

        return $query->getResult();
    }
}