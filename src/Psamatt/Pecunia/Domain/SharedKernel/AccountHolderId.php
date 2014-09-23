<?php

namespace Psamatt\Pecunia\Domain\SharedKernel;

/**
 * Value object to hold account number id
 *
 */
class AccountHolderId
{
	private $id;

	public function __construct($id)
	{
		$this->id = $id;
	}

	/** @return string */
	public function __toString()
	{
		return (string)$this->id;
	}
}