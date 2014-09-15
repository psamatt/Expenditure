<?php

namespace Psamatt\Pecunia\Domain\Saving\Tests\ValueObject;

use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingTarget;
use Psamatt\Pecunia\Domain\SharedKernel\CurrencyFactory;

use Money\Money;

class SavingTargetTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		CurrencyFactory::setCurrencyName('GBP');
	}

	/** @expectedException InvalidArgumentException */
	public function testDateInPast()
	{
		new SavingTarget(new \DateTime('2000-01-01'), new Money(200, CurrencyFactory::getCurrency()));
	}

	public function testDateInFuture()
	{
		new SavingTarget(new \DateTime('+1 year'), new Money(200, CurrencyFactory::getCurrency()));
	}
}