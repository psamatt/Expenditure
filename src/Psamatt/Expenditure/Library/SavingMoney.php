<?php

namespace Psamatt\Expenditure\Library;

class SavingMoney
{
    /**
     * The amount to add to savings
     *
     * @var integer
     */
    private $amount;

    /**
     * Constructor
     *
     */
    public function __construct($amount)
    {
        $this->amount = $amount;
    }
    
    /**
     * Get the amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }
}