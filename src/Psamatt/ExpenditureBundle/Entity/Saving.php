<?php

namespace Psamatt\ExpenditureBundle\Entity;

use Carbon\Carbon as CarbonDateTime;

use Psamatt\Expenditure\Library\SavingMoney;

use Doctrine\ORM\Mapping as ORM;

/**
 * Used to store all savings
 *
 * Psamatt\ExpenditureBundle\Entity\Saving
 *
 * @ORM\Table(name="savings")
 * @ORM\Entity()
 */
class Saving
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Title of what the saving is to achieve
     *
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    protected $title;

    /**
     * The target date to reach of the saving amount
     *
     * @var DateTime $target_date
     *
     * @ORM\Column(name="target_date", type="date", nullable=true)
     */
    protected $target_date;
    
    /**
     * The target amount you want to save
     *
     * @var float $target_amount
     *
     * @ORM\Column(name="target_amount", type="float")
     */
    protected $target_amount;
    
    /**
     * The amount currently saved
     *
     * @var float $saved_amount
     *
     * @ORM\Column(name="saved_amount", type="float")
     */
    protected $saved_amount;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="savings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
	protected $user;
	
	/**
     * The number of pay days remaining
     *
     * @var integer
     * @access private
     */
    private $numPayDaysRemaining = null;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set title
     *
     * @param string $title
     *
     * @return Saving
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set target_date
     *
     * @param \DateTime $targetDate
     *
     * @return Saving
     */
    public function setTargetDate($targetDate)
    {
        $this->target_date = $targetDate;

        return $this;
    }

    /**
     * Get target_date
     *
     * @return \DateTime 
     */
    public function getTargetDate()
    {
        return $this->target_date;
    }

    /**
     * Set target_amount
     *
     * @param float $targetAmount
     *
     * @return Saving
     */
    public function setTargetAmount($targetAmount)
    {
        $this->target_amount = $targetAmount;

        return $this;
    }

    /**
     * Get target_amount
     *
     * @return float 
     */
    public function getTargetAmount()
    {
        return $this->target_amount;
    }
    
    /**
     * Is the amount specified over the target amount
     *
     * @param integer $amount
     * @param boolean result
     */
    public function isOverTargetAmount($amount)
    {
        return (float)$amount > $this->target_amount;
    }

    /**
     * Set saved_amount
     *
     * @param float $savedAmount
     *
     * @return Saving
     */
    public function setSavedAmount($savedAmount)
    {
        $this->saved_amount = $this->isOverTargetAmount($savedAmount)? $this->target_amount: $savedAmount;

        return $this;
    }

    /**
     * Get saved_amount
     *
     * @return float 
     */
    public function getSavedAmount()
    {
        return $this->saved_amount;
    }

    /**
     * Set user
     *
     * @param \Psamatt\ExpenditureBundle\Entity\User $user
     *
     * @return Saving
     */
    public function setUser(\Psamatt\ExpenditureBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Psamatt\ExpenditureBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Add money
     * 
     * @param integer $money
     */
    public function addMoney(SavingMoney $money)
    {
        $this->saved_amount += $money->getAmount();
    }
    
    /**
     * Has the user reached their goal
     *
     * @return boolean
     */
    public function isGoalReached()
    {
        return $this->saved_amount >= $this->target_amount;
    }
    
    /**
     * Get the percent progress
     *
     * @return int
     */
    public function getPercentProgress()
    {
        return round(($this->saved_amount / $this->target_amount) * 100);
    }
    
    /**
     * Get the amount of savings remaining
     *
     * @return integer
     */
    public function getAmountRemaining()
    {
        return $this->target_amount - $this->saved_amount;
    }
    
    /**
     * Get the number of pay days remaining
     *
     * @param integer $payDayOfMonth
     * @param DateTime|null $fromDate The date to start from. If null, then todays date is used
     * @param boolean $fresh Get a fresh value
     * @return integer
     */
    public function getNumberOfPayDaysRemaining($payDayOfMonth = null, $fromDate = null, $fresh = false)
    {
        if ($this->numPayDaysRemaining !== null && !$fresh) {
            return $this->numPayDaysRemaining;
        }
    
        $sameMonth = true;
        $payDaysRemaining = 0;
        $fromDate = $fromDate instanceof \DateTime? $fromDate: new \DateTime;
        
        if ($this->target_date < $fromDate) {
            return $payDaysRemaining;
        }
        
        $targetDateDay = $this->target_date->format('j');
        
        // if the start and end date are not in the same month
        if ($this->target_date->format('Y\-n') != $fromDate->format('Y\-n')) {
        
            $sameMonth = false;

            $payDaysRemaining += CarbonDateTime::instance($fromDate)->addMonth()->startOfMonth()->diffInMonths(CarbonDateTime::instance($this->target_date)->startOfMonth());
            
            // if the ending date is after the pay day of the month
            if ($targetDateDay >= $payDayOfMonth) {
                $payDaysRemaining++;
            }
            
        }
        
        // if the from date is before the pay of the month and its not the same month, if same month, then the target date 
        // has got to before the end date
        if ($fromDate->format('j') <= $payDayOfMonth && (!$sameMonth || ($sameMonth && $payDayOfMonth < $targetDateDay))) {
            $payDaysRemaining++;
        }

        return $this->numPayDaysRemaining = $payDaysRemaining;
    }
    
    /**
     * Get the amount of money to save per pay day to reach the target
     *
     * @param boolean $payDayOfMonth
     * @param DateTime|null $fromDate The date to start from. If null, then todays date is used
     * @param boolean $toTwoDecimals Whether to format the value with two decimal places
     * @return integer
     */
    public function getAmountPerMonth($payDayOfMonth, $fromDate = null, $toTwoDecimals = true)
    {
        $numPayDaysRemaining = $this->getNumberOfPayDaysRemaining($payDayOfMonth, $fromDate);
        $amountRemaining = $this->getAmountRemaining();
        
        $amountPerMonth = $numPayDaysRemaining == 0? $amountRemaining: $amountRemaining / $numPayDaysRemaining;
        
        if ($toTwoDecimals) {
            $amountPerMonth = number_format($amountPerMonth, 2);
        }
        
        return $amountPerMonth;
    }
}