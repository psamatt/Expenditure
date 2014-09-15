<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Domain\Expenditure;

use Psamatt\Pecunia\Domain\Expenditure\Repository\IDefaultRecurringMonthExpenditureRepository;
use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultRecurringMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;
use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Expenditure.DefaultRecurringMonthExpenditure.repository")
 */
class DefaultRecurringMonthExpenditureRepository implements IDefaultRecurringMonthExpenditureRepository
{
    private $em;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.write_entity_manager")
     * })
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
    }

    /** {inheritdoc} */
    public function find(RecurringExpenditureId $recurringExpenditureId)
    {
        return $this->em->find(DefaultRecurringMonthExpenditure::fqcn(), $recurringExpenditureId);
    }

    /** {inheritdoc} */
    public function findAllByAccountHolder(AccountHolderId $accountHolderId)
    {
        return $this->em->getRepository(DefaultRecurringMonthExpenditure::fqcn())->findBy(['accountHolderId' => $accountHolderId]);
    }

    /** {inheritdoc} */
    public function save(DefaultRecurringMonthExpenditure $defaultRecurringMonthExpenditure)
    {
        $this->em->persist($defaultRecurringMonthExpenditure);
    }

    /** {inheritdoc} */
    public function remove(DefaultRecurringMonthExpenditure $defaultRecurringMonthExpenditure)
    {
        $this->em->remove($defaultRecurringMonthExpenditure);
    }
}