<?php

namespace Psamatt\Pecunia\Query\Repository\AccountHolder;

interface IAccountHolderGlobalRepository
{
    /**
     * Find
     * @param integer accountHolderId
     * @return ViewModel
     */
    public function find($accountHolderId);
}