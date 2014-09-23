<?php

namespace Psamatt\Pecunia\Domain\AccountHolder\Repository;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;

interface IAccountHolderRepository
{
    /**
     * Find the account holder
     *
     * @param AccountHolderId $AccountHolderId
     * @return AccountHolder
     */
    public function find(AccountHolderId $accountHolderId);
}