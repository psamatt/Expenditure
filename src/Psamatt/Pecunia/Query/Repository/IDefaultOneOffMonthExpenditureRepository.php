<?php

namespace Psamatt\Pecunia\Query\Repository;

interface IDefaultOneOffMonthExpenditureRepository
{
    /**
     * Find all the default one off month expenditures
     *
     * @param string $accountHolderId
     */
    public function findAll($accountHolderId);
}