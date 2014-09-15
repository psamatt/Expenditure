<?php

namespace Psamatt\Pecunia\Query\Repository\AccountHolder;

interface IAccountHolderNameRepository
{
    /**
     * Find
     * @param integer accountHolderId
     * @return ViewModel
     */
    public function find($accountHolderId);
}