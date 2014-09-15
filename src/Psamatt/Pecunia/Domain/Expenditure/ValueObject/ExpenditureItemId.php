<?php

namespace Psamatt\Pecunia\Domain\Expenditure\ValueObject;

use Rhumsaa\Uuid\Uuid;

/**
 * Class for a ExpenditureItemId
 *
 */
class ExpenditureItemId
{
	private $id;

	public function __construct($id)
	{
		$this->id = $id;
	}

	/** bind an id */
	public static function bind($id)
	{
		return new self($id);
	}

	/**
	 * Generate a new ExpenditureItemId
	 *
	 * @return ExpenditureItemId
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
