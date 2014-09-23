<?php

namespace Psamatt\Pecunia\Domain\Saving\Tests\Entity;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\SharedKernel\CurrencyFactory;

use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingId;
use Psamatt\Pecunia\Domain\Saving\Entity\Saving;
use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingTarget;

use Money\Money;

class SavingTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		CurrencyFactory::setCurrencyName('GBP');
	}

	public function testNewDescription()
	{
		$saving = Saving::setup(SavingId::generate(), new AccountHolderId(1), 'Foo', new SavingTarget(new \DateTime('+2 years'), new Money(200, CurrencyFactory::getCurrency())));

		$saving->describe($newDescription = 'Bar');

		$this->assertEquals($newDescription, $saving->getDescription());
	}

	public function testDepositFiftyGBP()
	{
		$saving = Saving::setup(SavingId::generate(), new AccountHolderId(1), 'Foo', new SavingTarget(new \DateTime('+2 years'), new Money(200, CurrencyFactory::getCurrency())));

		$saving->deposit($savedAmount = new Money(50, CurrencyFactory::getCurrency()));

		$this->assertEquals($savedAmount->getAmount(), $saving->getSavedAmount());
		$this->assertEquals(150, $saving->getAmountRemaining());
	}

	public function testDepositMoreThanTarget()
	{
		$saving = Saving::setup(SavingId::generate(), new AccountHolderId(1), 'Foo', new SavingTarget(new \DateTime('+2 years'), new Money(200, CurrencyFactory::getCurrency())));

		$saving->deposit($savedAmount = new Money(500, CurrencyFactory::getCurrency()));

		$this->assertEquals($savedAmount->getAmount(), $saving->getSavedAmount());
	}
}