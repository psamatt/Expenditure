<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Tests\Entity;

use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;

class CalendarPeriodTest extends \PHPUnit_Framework_TestCase
{
	public function testValidMonthAndYear()
	{
		$this->assertInstanceOf('Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod', CalendarPeriod::forMonth(4, 2013));
	}

	public function testCalendarMonthAsDateTime()
	{
		$cm = CalendarPeriod::forMonth(4, 2013);
		$this->assertInstanceOf('DateTime', $cm->getAsDateTime());
	}

	/** @expectedException InvalidArgumentException */
	public function testInvalidMonthLessThanOne()
	{
		CalendarPeriod::forMonth(0, 2013);
	}

	/** @expectedException InvalidArgumentException */
	public function testInvalidMonthGreaterThanTwelve()
	{
		CalendarPeriod::forMonth(50, 2013);
	}

	/** @expectedException InvalidArgumentException */
	public function testInvalidMonthAsString()
	{
		CalendarPeriod::forMonth("3", 2013);
	}

	/** @expectedException InvalidArgumentException */
	public function testInvalidYearAs1900()
	{
		CalendarPeriod::forMonth(2, 1900);
	}

	/** @expectedException InvalidArgumentException */
	public function testInvalidYearAsString()
	{
		CalendarPeriod::forMonth(2, "2013");
	}
}