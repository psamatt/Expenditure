<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Repository;

use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultOneOffMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\OneOffPaymentDueDate;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;

interface IDefaultOneOffMonthExpenditureRepository
{
    /**
     * Find
     *
     * @param RecurringExpenditureId $recurringExpenditureId
     * @return DefaultOneOffMonthExpenditure
     */
    public function find(RecurringExpenditureId $recurringExpenditureId);

    /**
     * Find all instances by an account holder that are due
     *
     * @param AccountHolderId $accountHolderId
     * @param DateTime $dueDate
     * @return DefaultOneOffMonthExpenditure
     */
    public function findDueByAccountHolder(AccountHolderId $accountHolderId, \DateTime $dueDate);

	/**
	 * Save
	 *
	 * @param DefaultOneOffMonthExpenditure $defaultOneOffMonthExpenditure
	 */
	public function save(DefaultOneOffMonthExpenditure $defaultOneOffMonthExpenditure);

    /**
     * Remove
     *
     * @param DefaultOneOffMonthExpenditure $DefaultOneOffMonthExpenditure
     */
    public function remove(DefaultOneOffMonthExpenditure $defaultOneOffMonthExpenditure);
}