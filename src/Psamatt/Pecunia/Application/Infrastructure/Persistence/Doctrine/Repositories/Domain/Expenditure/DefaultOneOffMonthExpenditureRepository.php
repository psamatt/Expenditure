<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Domain\Expenditure;

use Psamatt\Pecunia\Domain\Expenditure\Repository\IDefaultOneOffMonthExpenditureRepository;
use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultOneOffMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Expenditure.DefaultOneOffMonthExpenditure.repository")
 */
class DefaultOneOffMonthExpenditureRepository implements IDefaultOneOffMonthExpenditureRepository
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
        return $this->em->find(DefaultOneOffMonthExpenditure::fqcn(), $recurringExpenditureId);
    }

    /** {inheritdoc} */
    public function findDueByAccountHolder(AccountHolderId $accountHolderId, \DateTime $dueDate)
    {
        return $this->em->getRepository(DefaultOneOffMonthExpenditure::fqcn())->findBy(['accountHolderId' => $accountHolderId, 'dueDate' => $dueDate]);
    }

    /** {inheritdoc} */
    public function save(DefaultOneOffMonthExpenditure $defaultOneOffMonthExpenditure)
    {
        $this->em->persist($defaultOneOffMonthExpenditure);
    }

    /** {inheritdoc} */
    public function remove(DefaultOneOffMonthExpenditure $defaultOneOffMonthExpenditure)
    {
        $this->em->remove($defaultOneOffMonthExpenditure);
    }
}