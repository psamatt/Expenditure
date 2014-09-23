<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Tests\Entity;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;
use Psamatt\Pecunia\Domain\SharedKernel\CurrencyFactory;

use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultRecurringMonthExpenditure;

use Money\Money;

class DefaultRecurringMonthExpenditureTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		CurrencyFactory::setCurrencyName('GBP');
	}

	public function testNewDescription()
	{
		$instance = $this->getInstance();

		$instance->describe('Bar');
		$this->assertEquals('Bar', $instance->getDescription());
	}

	public function testRepriceItem()
	{
		$instance = $this->getInstance();
		$instance->reprice($newAmount = new Money(999, CurrencyFactory::getCurrency()));

		$this->assertEquals($newAmount, $instance->getAmount());
	}

	public function getInstance()
	{
		return new DefaultRecurringMonthExpenditure(RecurringExpenditureId::generate(), new AccountHolderId(1), 'Foo', new Money(100, CurrencyFactory::getCurrency()));
	}
}