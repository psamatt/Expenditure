<?php

namespace Psamatt\Pecunia\Application\Infrastructure\Persistence\Doctrine\Repositories\Domain\AccountHolder;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\AccountHolder\Entity\AccountHolder;
use Psamatt\Pecunia\Domain\AccountHolder\Repository\IAccountHolderRepository;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service("Pecunia.AccountHolder.repository")
 */
class AccountHolderRepository implements IAccountHolderRepository
{
    private $em;

    /**
     * @DI\InjectParams({
     *     "em" = @DI\Inject("doctrine.orm.write_entity_manager")
     * })
     */
    public function __construct($em)
    {
        $this->em = $em;
    }

    /** {inheritdoc} */
    public function find(AccountHolderId $accountHolderId)
    {
        $accountHolder = $this->em->find(AccountHolder::fqcn(), $accountHolderId);

        if (null === $accountHolder) {
            throw new \InvalidArgumentException('Account holder doesnt exist');
        }

        return $accountHolder;
    }
}