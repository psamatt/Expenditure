<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Query;

use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultOneOffMonthExpenditure;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Query.DefaultOneOffMonthExpenditure.repository")
 */
class DefaultOneOffMonthExpenditureORMRepository implements \Psamatt\Pecunia\Query\Repository\IDefaultOneOffMonthExpenditureRepository
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
                FROM ' . DefaultOneOffMonthExpenditure::fqcn() . ' drme
                WHERE drme.accountHolderId = :accountHolderId
                    ORDER BY drme.dueDate ASC');

        $query->setParameters(['accountHolderId' => $accountHolderId]);

        return $query->getResult();
    }
}