<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Repository;

use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultRecurringMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;
use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;

interface IDefaultRecurringMonthExpenditureRepository
{
    /**
     * Find
     *
     * @param RecurringExpenditureId $recurringExpenditureId
     * @return DefaultRecurringMonthExpenditure
     */
    public function find(RecurringExpenditureId $recurringExpenditureId);

    /**
     * Find all instances by an account holder
     *
     * @param AccountHolderId $accountHolderId
     * @return DefaultRecurringMonthExpenditure
     */
    public function findAllByAccountHolder(AccountHolderId $accountHolderId);

	/**
	 * Save
	 *
	 * @param DefaultRecurringMonthExpenditure $defaultRecurringMonthExpenditure
	 */
	public function save(DefaultRecurringMonthExpenditure $defaultRecurringMonthExpenditure);

    /**
     * Remove
     *
     * @param DefaultRecurringMonthExpenditure $defaultRecurringMonthExpenditure
     */
    public function remove(DefaultRecurringMonthExpenditure $defaultRecurringMonthExpenditure);
}