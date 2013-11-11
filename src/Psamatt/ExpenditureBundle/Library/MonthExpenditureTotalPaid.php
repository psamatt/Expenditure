<?php

namespace Psamatt\ExpenditureBundle\Library;

use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;

/**
 * A class that counts up how much has been paid of each MonthExpenditure object
 *
 * This class would implement countable but it casts the count to an integer - we require a float
 */
class MonthExpenditureTotalPaid
{
    /**
     * Constructor
     *
     * @param array $items
     */
    public function __construct(array $items) 
    {
        $this->items = $items;
    }
    
    /**
     * Count up how much has been paid and return the float
     *
     * @return float
     */
    public function count()
    {
        $total = 0;
        
        for ($i = 0, $j = count($this->items); $i < $j; $i++) {
        
            $item = $this->items[$i];
            
            if (is_object($item) && $item instanceof MonthExpenditure) {
                $total += $item->getAmountPaid();
            }
        }
        
        return $total;
    }
}