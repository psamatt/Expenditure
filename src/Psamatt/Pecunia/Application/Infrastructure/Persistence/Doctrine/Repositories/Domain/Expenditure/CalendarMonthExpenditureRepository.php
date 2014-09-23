<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Domain\Expenditure;

use Psamatt\Pecunia\Domain\Expenditure\Repository\ICalendarMonthExpenditureRepository;
use Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditure;
use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Expenditure.CalendarMonthExpenditure.repository")
 */
class CalendarMonthExpenditureRepository implements ICalendarMonthExpenditureRepository
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
    public function findByDateAndAccountHolder(CalendarPeriod $calendarPeriod, AccountHolderId $accountHolderId)
    {
        $qb = $this->em->createQueryBuilder();

        $query = $qb->
            select('cme')
               ->from(CalendarMonthExpenditure::fqcn(), 'cme')
               ->where($qb->expr()->andX(
                    $qb->expr()->eq('cme.calendarDate', ':calendarDate'),
                    $qb->expr()->eq('cme.accountHolderId', ':accountHolderId')
               ))
            ->setMaxResults(1)
            ->setParameters([
                        'calendarDate' => $calendarPeriod->getAsDateTime(),
                        'accountHolderId' => $accountHolderId,
                ])
            ->getQuery();

        $result = $query->getOneOrNullResult();

        if ($result == null) {
            throw new \InvalidArgumentException('CalendarMonthExpenditure does not exist');
        }

        return $result;
    }

    /** {inheritdoc}*/
    public function save(CalendarMonthExpenditure $calendarMonthExpenditure)
    {
        $this->em->persist($calendarMonthExpenditure);
    }
}