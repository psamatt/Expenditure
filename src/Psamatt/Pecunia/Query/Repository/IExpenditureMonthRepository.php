<?php

namespace Psamatt\Pecunia\Query\Repository;

interface IExpenditureMonthRepository
{
    /**
     * Find the latest by the account holder id
     *
     * @param string $accountHolderId
     * @return ExpenditureMonth
     */
    public function findLatestByUser($accountHolderId);

    /**
     * Find by month
     *
     * @param string $accountHolderId
     * @param \DateTime $monthYear
     */
    public function findByMonth($accountHolderId, \DateTime $monthYear);

    /**
     * Find a historic overview
     *
     * @param integer $accountHolderId
     */
    public function findHistoricOverview($accountHolderId);
}