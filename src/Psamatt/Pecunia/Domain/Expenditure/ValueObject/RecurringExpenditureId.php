<?php

namespace Psamatt\Pecunia\Domain\Expenditure\ValueObject;

use Rhumsaa\Uuid\Uuid;

/**
 * Class for a RecurringExpenditureId
 *
 */
class RecurringExpenditureId
{
	private $id;

	private function __construct($id)
	{
		$this->id = $id;
	}

	/** bind an id */
	public static function bind($id)
	{
		return new self($id);
	}

	/**
	 * Generate a new RecurringExpenditureId
	 *
	 * @return RecurringExpenditureId
	 */
	public static function generate()
	{
		return new self(Uuid::uuid4());
	}

	/** @return string */
	public function __toString()
	{
		return (string)$this->id;
	}
}
