<?php

namespace Psamatt\Pecunia\Domain\AccountHolder\Entity;

use Psamatt\Pecunia\Domain\SharedKernel\AccountHolderId;

use Psamatt\Pecunia\Aggregate\AccountHolder\ValueObject\Name;
use Psamatt\Pecunia\Aggregate\AccountHolder\ValueObject\EmailAddress;
use Psamatt\Pecunia\Aggregate\AccountHolder\UserState;

use Psamatt\Pecunia\Aggregate\Common\ValueObject\Day;

use Money\Currency;

/**
 * Used to store all users throughout the system
 */
class AccountHolder
{
    /** @var integer $id */
    protected $id;

    /** firstname */
    protected $firstname;

    /** surname */
    protected $surname;

    /** salutation */
    protected $salutation;

    /** Email address of user */
    protected $emailAddress;

    /** The day of the month the user gets paid */
    protected $paidDay;

    /** Currency */
    protected $currency;

    /** The status of the user */
    protected $status;

	public function __construct(Name $name, EmailAddress $emailAddress, $paidDay, Currency $currency)
    {
    	$this->rename($name);
        $this->emailAddress = $emailAddress;
        $this->paidDay = $day;
        $this->currency = $currency->getName();

        $this->status = UserState::ACTIVE;
    }

    /** @return string */
    public static function fqcn()
    {
        return __CLASS__;
    }

    public static function register(Name $name, EmailAddress $emailAddress, Day $paidDay)
    {
        return new self($name, $emailAddress, $paidDay);
    }

    /** @return AccountHolderId */
    public function getId()
    {
        return new AccountHolderId($this->id);
    }

    /** @return Currency */
    public function getCurrency()
    {
        return new Currency($this->currency);
    }
}