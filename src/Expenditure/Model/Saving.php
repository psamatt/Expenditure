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
        );
    }
    
    /**
     * Get the target date
     *
     * @param $endOfMonth Whether to get the target date at the end of the month or not. Default false
     * return DateTime
     */
    public function getTargetDate($endOfMonth = false)
    {
        if (!$endOfMonth) {
            return $this->target_date;
        }
    
        $targetDate = CarbonDateTime::instance($this->target_date);
        
        return $targetDate->endOfMonth();
    }
    
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
     * Get th number of months remaining
     *
     * @param boolean $includeCurrentMonth
     * @return integer
     */
    public function getNumMonthsRemaining($includeCurrentMonth = true)
    {
        $today = new \DateTime();
        
        if ($this->target_date < $today) {
            return 0;
        }
        
        $targetDate = $this->getTargetDate(true);
        
        $dateDifference = $today->diff($targetDate);
        return ($dateDifference->y > 0? $dateDifference->y * 12: 0) + $dateDifference->m + ($includeCurrentMonth === true? 1:0);
    }
    
    /**
     * Get the amount of money to save per month to reach the target
     *
     * @param boolean $includeCurrentMonth
     * @return integer
     */
    public function getAmountPerMonth($includeCurrentMonth)
    {
        $numMonthsRemaining = $this->getNumMonthsRemaining($includeCurrentMonth);
        $amountRemaining = $this->getAmountRemaining();
        
        return $numMonthsRemaining == 0? $amountRemaining: $amountRemaining / $numMonthsRemaining;
    }
}