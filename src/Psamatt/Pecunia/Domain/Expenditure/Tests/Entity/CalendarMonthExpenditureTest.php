<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Tests\Entity;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\SharedKernel\CurrencyFactory;

use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\ExpenditureItemId;
use Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditure;

use Money\Money;

class CalendarMonthExpenditureTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		CurrencyFactory::setCurrencyName('GBP');
	}

	public function testMonthlyIncomeIsAsExpected()
	{
		$monthExpenditure = self::getInstance($amount = 200);

		$this->assertInstanceOf('Money\Money', $monthExpenditure->getIncome());
		$this->assertEquals($amount, $monthExpenditure->getIncome()->getAmount());
	}

	public function testTotalOutgoingEquatesToAmountOfAllItems()
	{
		$monthExpenditure = self::getInstance(1000);
		$monthExpenditure->addItem(ExpenditureItemId::generate(), 'Foo', $totalItemAmount = new Money(100, CurrencyFactory::getCurrency()));

		$this->assertEquals($totalItemAmount, $monthExpenditure->getTotalOutgoing());
	}

	public function testTotalPaidEquatesToAmountPaidOfAllItems()
	{
		$monthExpenditure = self::getInstance(1000);
		$monthExpenditure->addItem($itemId = ExpenditureItemId::generate(), 'Foo', $totalItemAmount = new Money(100, CurrencyFactory::getCurrency()));
		$monthExpenditure->markAsPaid($itemId);

		$this->assertEquals($totalItemAmount, $monthExpenditure->getTotalPaid());
	}

	public function testTotalPaidOfOneItemEquatesToAmountPaidOfOneItemOfMultipleItems()
	{
		$monthExpenditure = self::getInstance(1000);
		$monthExpenditure->addItem($itemId = ExpenditureItemId::generate(), 'Foo', $itemAmount = new Money(100, CurrencyFactory::getCurrency()));
		$monthExpenditure->addItem(ExpenditureItemId::generate(), 'Bar', new Money(200, CurrencyFactory::getCurrency()));

		$monthExpenditure->markAsPaid($itemId);

		$this->assertEquals(new Money(300, CurrencyFactory::getCurrency()), $monthExpenditure->getTotalOutgoing());
		$this->assertEquals($itemAmount, $monthExpenditure->getTotalPaid());
	}

	public function testPartialPaymentOfOneItem()
	{
		$monthExpenditure = self::getInstance(1000);
		$monthExpenditure->addItem($itemId = ExpenditureItemId::generate(), 'Foo', new Money(100, CurrencyFactory::getCurrency()));
		$monthExpenditure->addPartialPayment($itemId, new Money(50, CurrencyFactory::getCurrency()));

		$this->assertEquals(new Money(50, CurrencyFactory::getCurrency()), $monthExpenditure->getTotalPaid());
	}

	public function testPartialPaymentOfMoreThanPriceOfOneItem()
	{
		$monthExpenditure = self::getInstance(1000);
		$monthExpenditure->addItem($itemId = ExpenditureItemId::generate(), 'Foo', $itemAmount = new Money(100, CurrencyFactory::getCurrency()));
		$monthExpenditure->addPartialPayment($itemId, new Money(200, CurrencyFactory::getCurrency()));

		$this->assertEquals($itemAmount, $monthExpenditure->getTotalPaid());
	}

	public function testAddTwoItemsRemoveOneFindNewTotalOutgoing()
	{
		$monthExpenditure = self::getInstance(1000);
		$monthExpenditure->addItem($itemId = ExpenditureItemId::generate(), 'Foo', new Money(100, CurrencyFactory::getCurrency()));
		$monthExpenditure->addItem(ExpenditureItemId::generate(), 'Bar', $itemAmount = new Money(200, CurrencyFactory::getCurrency()));

		$monthExpenditure->removeItem($itemId);

		$this->assertEquals($itemAmount, $monthExpenditure->getTotalOutgoing());
	}

	public static function getInstance($incomeAmount)
	{
		return new CalendarMonthExpenditure(new AccountHolderId(1), CalendarPeriod::forMonth(4, 2000), new Money($incomeAmount, CurrencyFactory::getCurrency()));
	}
}