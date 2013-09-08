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
     * @param boolean $fresh Get a fresh value
     * @return integer
     */
    public function getNumberOfPayDaysRemaining($payDayOfMonth = null, $fresh = false)
    {
        if ($this->numPayDaysRemaining !== null && !$fresh) {
            return $this->numPayDaysRemaining;
        }
    
        $payDaysRemaining = 0;
        $today = new \DateTime();
        
        if ($this->target_date < $today) {
            return $payDaysRemaining;
        }
        
        $payDaysRemaining = CarbonDateTime::now()->addMonth()->startOfMonth()->diffInMonths(CarbonDateTime::instance($this->target_date)->startOfMonth());
        
        if ($today->format('j') < $payDayOfMonth) {
            $payDaysRemaining++;
        }

        if ($this->target_date->format('j') >= $payDayOfMonth) {
            $payDaysRemaining++;
        }

        return $this->numPayDaysRemaining = $payDaysRemaining;
    }
    
    /**
     * Get the amount of money to save per pay day to reach the target
     *
     * @param boolean $payDayOfMonth
     * @return integer
     */
    public function getAmountPerMonth($payDayOfMonth)
    {
        $numPayDaysRemaining = $this->getNumberOfPayDaysRemaining($payDayOfMonth);
        $amountRemaining = $this->getAmountRemaining();
        
        return $numPayDaysRemaining == 0? $amountRemaining: $amountRemaining / $numPayDaysRemaining;
    }
}