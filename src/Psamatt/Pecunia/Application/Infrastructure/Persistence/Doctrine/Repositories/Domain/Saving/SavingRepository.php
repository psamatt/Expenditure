<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Domain\Saving;

use Psamatt\Pecunia\Domain\Saving\Repository\ISavingRepository;
use Psamatt\Pecunia\Domain\Saving\Entity\Saving;
use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingId;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.Saving.Saving.repository")
 */
class SavingRepository implements ISavingRepository
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

    /** {inheritDoc} */
    public function find(SavingId $id)
    {
        $saving = $this->em->find(Saving::fqcn(), $id);

        if (null === $saving) {
            throw new \InvalidArgumentException('Item not found');
        }

        return $saving;
    }

    /** {inheritDoc} */
    public function save(Saving $saving)
    {
        $this->em->persist($saving);
    }

    /** {inheritDoc} */
    public function remove(Saving $saving)
    {
        $this->em->remove($saving);
    }
}