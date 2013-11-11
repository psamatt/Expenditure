<?php

namespace Psamatt\ExpenditureBundle\Services;

use Psamatt\ExpenditureBundle\Repository\RepositoryInterface;
use Psamatt\ExpenditureBundle\Entity\User;

/**
 * All basic page actions
 *
 */
class BasePageAction
{   
    /**
     * Is this item part of the user
     *
     * @param object $item
     * @param User $user
     * @return boolean
     */
    public function isOwnedByAdmin($item, User $user)
    {
        return $this->repository->isOwnedByAdmin($item, $user);
    }
}