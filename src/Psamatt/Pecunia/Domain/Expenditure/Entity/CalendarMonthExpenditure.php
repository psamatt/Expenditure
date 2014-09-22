<?php

namespace Psamatt\Pecunia\Domain\Expenditure\Entity;

use Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditureItem;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\ExpenditureItemId;

use Doctrine\Common\Collections\ArrayCollection;

use Money\Money;
use Money\Currency;

/**
 * Used to store all monthly headers throughout the system
 */
class CalendarMonthExpenditure
{
    /** @var integer $id */
    protected $id;

    /** The calendar date of the month */
    protected $calendarDate;

    /** The monthly income for this month */
    protected $income;

    /** The monthly currency */
    protected $currency;

    /** @param string */
    protected $accountHolderId;

    /** @var array */
    protected $expenditureItems;

    /** total paid */
    private $totalPaid = 0;
    /** total outgoing */
    private $totalOutgoing = 0;

    public function __construct(CalendarPeriod $calendarDate, AccountHolderId $accountHolderId, Money $income)
    {
    	$this->expenditureItems = new ArrayCollection;

    	$this->calendarDate = $calendarDate->getAsDateTime();
        $this->accountHolderId = (string)$accountHolderId;
    	$this->income = $income->getAmount();
        $this->currency = (string)$income->getCurrency();
    }

    /** @return string */
    public static function fqcn()
    {
        return __CLASS__;
    }

    /**
     * Add an expenditure item
     *
     * @param ExpenditureItemId $itemId
     * @param string $description
     * @param Money $amount
     */
    public function addItem(ExpenditureItemId $itemId, $description, Money $amount)
    {
        $this->expenditureItems[] = new CalendarMonthExpenditureItem($itemId, $this, $description, $amount);

        $this->calculateTotalOutgoing();
    }

    /**
     * Remove an expenditure item
     */
    public function removeItem(ExpenditureItemId $itemID)
    {
        foreach ($this->expenditureItems as $index => $expenditureItem) {
            if ($expenditureItem->getId() == $itemID) {
                $this->expenditureItems->removeElement($expenditureItem);
                break;
            }
        }

        $this->calculateTotalOutgoing();
    }

    /**
     * Add a partial payment
     *
     * @param ExpenditureItemId $itemID
     * @param Money $payment
     */
    public function addPartialPayment(ExpenditureItemId $itemID, Money $payment)
    {
        foreach ($this->expenditureItems as $expenditureItem) {
            if ($expenditureItem->getId() == $itemID) {
                $expenditureItem->addPayment($payment);
                break;
            }
        }

        $this->calculateTotalPaid();
    }

    /**
     * Mark an expenditure item as paid
     * @param ExpenditureItemId $itemID
     */
    public function markAsPaid(ExpenditureItemId $itemID)
    {
        foreach ($this->expenditureItems as $expenditureItem) {

            if ($expenditureItem->getId() == $itemID) {
                $expenditureItem->paid();
                break;
            }
        }

        $this->calculateTotalPaid();
    }

    /** @return DateTime get the qualifying month */
    public function getMonth()
    {
        return $this->calendarDate;
    }

    /** @exposure Query only */
    public function getId() { return $this->id; }
    public function getCalendarDate() { return $this->calendarDate; }
    public function getAmountStillToPay() { return $this->getTotalOutgoing()->subtract($this->getTotalPaid()); }
    public function getMoneyRemaining() { return $this->getIncome()->subtract($this->getTotalOutgoing()); }
    public function getExpenditureItems() { return $this->expenditureItems; }

    /**
     * Get the monthly income
     *
     * @return Money
     */
    public function getIncome()
    {
        return new Money($this->income, $this->getCurrency());
    }

    /** @return Money */
    public function getTotalOutgoing()
    {
        return new Money($this->totalOutgoing, $this->getCurrency());
    }

    /** @return Money */
    public function getTotalPaid()
    {
        return new Money($this->totalPaid, $this->getCurrency());
    }

    /** @return AccountHolderId */
    public function getAccountHolderId()
    {
        return new AccountHolderId($this->accountHolderId);
    }

    /** @return Currency */
    public function getCurrency()
    {
        return new Currency($this->currency);
    }

    /**
     * calculate the total amount outgoings
     */
    private function calculateTotalOutgoing()
    {
        $totalOutgoing = new Money(0, $this->getCurrency());

        foreach ($this->expenditureItems as $expenditureItem) {
            $totalOutgoing = $totalOutgoing->add($expenditureItem->getPrice());
        }

        $this->totalOutgoing = $totalOutgoing->getAmount();
    }

    /**
     * calculate the total amount paid
     */
    private function calculateTotalPaid()
    {
        $totalPaid = new Money(0, $this->getCurrency());

        foreach ($this->expenditureItems as $expenditureItem) {
            $totalPaid = $totalPaid->add($expenditureItem->getAmountPaid());
        }

        $this->totalPaid = $totalPaid->getAmount();
    }
}