<?php

namespace Psamatt\Pecunia\Domain\Saving\Entity;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;

use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingTarget;
use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingId;

use Money\Money;
use Money\Currency;

/**
 * Used to store all savings
 */
class Saving
{
    /** @id */
    protected $id;

    /** Description of what the saving is to achieve */
    protected $description;

    /** The target date to reach of the saving amount */
    protected $targetDate;

    /** The target amount you want to save */
    protected $targetAmount;

    /** The amount currently saved */
    protected $savedAmount;

    /** @var AccountHolderId */
	protected $accountHolderId;

    /** tmp class var */
    protected $currency;

    /**
     * Constructor
     *
     */
    private function __construct(SavingId $id, AccountHolderId $accountHolderId, $description, SavingTarget $target)
    {
        $this->id = $id;
        $this->accountHolderId = $accountHolderId;
        $this->description = $description;
        $this->targetDate = $target->getTargetDate();
        $this->targetAmount = $target->getTargetAmount()->getAmount();
        $this->savedAmount = 0;

        $this->currency = $target->getTargetAmount()->getCurrency();
    }

    /**
     * Set up a savings
     *
     * @param SavingId $id
     * @param AccountHolderId $accountHolderId
     * @param string $description
     * @param SavingTarget $target
     */
    public static function setup(SavingId $id, AccountHolderId $accountHolderId, $description, SavingTarget $target)
    {
        return new self($id, $accountHolderId, $description, $target);
    }

    /** @return string */
    public static function fqcn()
    {
        return __CLASS__;
    }

    /** @param string */
    public function describe($description)
    {
        $this->description = $description;
    }

    /**
     * Deposit funds
     *
     * @param Money $depositFunds
     */
    public function deposit(Money $depositFunds)
    {
        $savedAmount = (new Money($this->savedAmount, $depositFunds->getCurrency()))->add($depositFunds);

        $this->savedAmount = $savedAmount->getAmount();
    }

    /**
     * set a specific target
     *
     * @param SavingTarget $target
     */
    public function setTarget(SavingTarget $target)
    {
        $this->targetDate = $target->getTargetDate();
        $this->targetAmount = $target->getTargetAmount()->getAmount();
    }

    /** getters for view only */
    public function getId() { return $this->id; }
    public function getDescription() { return $this->description; }
    public function getTargetDate() { return $this->targetDate; }
    public function getSavedAmount() { return $this->savedAmount; }
    public function getTargetAmount() { return $this->targetAmount; }
    public function getAmountRemaining()  { return $this->targetAmount - $this->savedAmount; }
    public function isGoalReached() { return $this->savedAmount >= $this->targetAmount; }
    public function getPercentProgress() { return round(($this->savedAmount / $this->targetAmount) * 100); }
}