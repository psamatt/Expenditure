<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Query;

use Psamatt\Pecunia\Domain\Saving\Entity\Saving;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Query.Saving.repository")
 */
class SavingORMRepository implements \Psamatt\Pecunia\Query\Repository\ISavingRepository
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
        $query = $this->em->createQuery('SELECT s
                FROM ' . Saving::fqcn() . ' s
                WHERE s.accountHolderId = :accountHolderId
                    ORDER BY s.targetAmount DESC');

        $query->setParameters(['accountHolderId' => $accountHolderId]);

        return $query->getResult();
    }
}