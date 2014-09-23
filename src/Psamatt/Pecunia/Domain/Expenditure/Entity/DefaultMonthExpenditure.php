<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Entity;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;

use Money\Money;

/**
 * Used to store all monthly expenditure templates
 */
abstract class DefaultMonthExpenditure
{
    /** @var integer $id */
    protected $id;

    /** description of what the expenditure template is */
    protected $description;

    /** The price of the expenditure template */
    protected $price;

    protected $currency;

    /** @var aAcountHolderId */
	protected $accountHolderId;

	/**
	 * Constructor
	 *
	 */
	public function __construct(RecurringExpenditureId $id, AccountHolderId $accountHolderId, $description, Money $price)
	{
		$this->id = $id;
        $this->accountHolderId = $accountHolderId;
        $this->description = $description;
        $this->reprice($price);
	}

    /** @return string */
    public static function fqcn()
    {
        return __CLASS__;
    }

	/** @param string $description */
	public function describe($description)
	{
        $this->description = $description;
	}

	/** @param Money $price */
	public function reprice(Money $price)
	{
        $this->price = $price->getAmount();
        $this->currency = (string)$price->getCurrency();
	}

	/** @return string */
    public function getId() { return RecurringExpenditureId::bind($this->id); }
	public function getDescription() { return $this->description; }
    public function getPrice() { return Money::{$this->currency}($this->price); }

	public function getAmount()
	{
		return Money::{$this->currency}($this->price);
	}
}