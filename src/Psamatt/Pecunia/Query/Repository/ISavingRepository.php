<?php

namespace Psamatt\Pecunia\Query\Repository;

interface ISavingRepository
{
    /**
     * Find all the savings
     *
     * @param string $accountHolderId
     */
    public function findAll($accountHolderId);
}