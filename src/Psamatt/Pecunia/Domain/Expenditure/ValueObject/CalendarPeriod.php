<?php

namespace Psamatt\Pecunia\Domain\Expenditure\ValueObject;

use \DateTime;

class CalendarPeriod
{
	private $month;
	private $year;

	private function __construct($month, $year)
	{
		if (!is_integer($month) || (is_integer($month) && ($month < 1 || $month > 12))) {
			throw new \InvalidArgumentException('Month must be numeric and between 1 and 12');
		}

		if (!is_integer($year) || (is_integer($year) && ($year < 1950))) {
			throw new \InvalidArgumentException('Year must be numeric and not less than 1950');
		}

		$this->month = $month;
		$this->year = $year;
	}

	public static function forMonth($month, $year)
	{
		return new self($month, $year);
	}

	/** @return DateTime */
	public function getAsDateTime()
	{
		return DateTime::createFromFormat('d-m-Y H:i:s', $this->__toString());
	}

	/** @return string */
	public function __toString()
	{
		return sprintf('01-%d-%d 00:00:00', $this->month, $this->year);
	}
}