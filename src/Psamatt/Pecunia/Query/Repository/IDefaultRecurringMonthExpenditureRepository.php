<?php

namespace Psamatt\Pecunia\Query\Repository;

interface IDefaultRecurringMonthExpenditureRepository
{
    /**
     * Find all the default recurring month expenditures
     *
     * @param string $accountHolderId
     */
    public function findAll($accountHolderId);
}