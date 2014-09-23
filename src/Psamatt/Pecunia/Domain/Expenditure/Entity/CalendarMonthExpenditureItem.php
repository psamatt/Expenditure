<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Entity;

use Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\ExpenditureItemId;

use Money\Money;

/** Used to store all monthly expenditures */
class CalendarMonthExpenditureItem
{
    /** @id */
    protected $id;

    /** description of what the expenditure is */
    protected $description;

    /** The price of the expenditure */
    protected $price;

    /** The amount paid for the expenditure */
    protected $paidAmount = 0;

    /** MonthExpenditureHeader */
	protected $monthExpenditure;

	public function __construct(ExpenditureItemId $id, CalendarMonthExpenditure $monthExpenditure, $description, Money $price)
	{
        $this->id = (string)$id;
        $this->monthExpenditure = $monthExpenditure;
        $this->description = $description;
        $this->price = $price->getAmount();
        $this->paidAmount = 0;
	}

    public static function fqcn()
    {
        return __CLASS__;
    }

    /** @return */
    public function getId()
    {
        return new ExpenditureItemId($this->id);
    }

    /* @return Query only */
    public function getDescription() { return $this->description; }
    public function isPaid() { return $this->getAmountPaid()->compare($this->getPrice()) >= 0; }
    public function isPartialPaid() { return $this->getAmountPaid()->isPositive(); }

    /** Add a partial payment */
    public function addPayment(Money $payment)
    {
        $paidAmount = $this->getAmountPaid()->add($payment);

        $this->paidAmount = $paidAmount->getAmount();

        if ($this->paidAmount > $this->price) {
            $this->paid();
        }
    }

    /** item has been paid */
    public function paid()
    {
        $this->paidAmount = $this->price;
    }

    /** @return Money */
    public function getPrice()
    {
        return new Money($this->price, $this->monthExpenditure->getCurrency());
    }

    /** @return Money */
    public function getAmountPaid()
    {
        return new Money($this->paidAmount, $this->monthExpenditure->getCurrency());
    }
}