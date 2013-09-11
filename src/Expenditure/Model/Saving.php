<?php

namespace Expenditure\Model;

use Carbon\Carbon as CarbonDateTime;

class Saving extends \Spot\Entity
{
    protected static $_datasource = 'savings';

    public static function fields()
    {
        return array(
            'id' => array('type' => 'int', 'primary' => true, 'serial' => true),
            'title' => array('type' => 'string', 'required' => true),
            'target_date' => array('type' => 'datetime', 'required' => true),
            'target_amount' => array('type' => 'float', 'required' => true),
            'saved_amount' => array('type' => 'float', 'required' => false),
            'user_id' => array('type' => 'integer', 'required' => true),
        );
    }
    
    /**
     * The number of pay days remaining
     *
     * @var integer
     * @access private
     */
    private $numPayDaysRemaining = null;
    
    /**
     * Add money
     * 
     * @param integer $money
     */
    public function addMoney($money)
    {
        $this->saved_amount += $money;
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