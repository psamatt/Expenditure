<?php

namespace Psamatt\ExpenditureBundle\Tests\Twig\Extension;

use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;
use Psamatt\ExpenditureBundle\Library\MonthExpenditureTotalPrice;

class MonthExpenditureTotalPriceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group MonthExpenditureTotalPrice
     */
    public function testAmountWithValidItemsAsInteger()
    {
        $items = array();
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 10);
        $items[] = $monthExpenditure;
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 20);
        $items[] = $monthExpenditure;
        
        $totalPrice = new MonthExpenditureTotalPrice($items);
        $this->assertEquals(30, $totalPrice->count());
    }
    
    /**
     * @group MonthExpenditureTotalPrice 
     */
    public function testAmountWithParticularValidItemsAsInteger()
    {
        $items = array();
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 10);
        $items[] = $monthExpenditure;
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 20);
        $items[] = $monthExpenditure;
        
        $items[] = 10;
        
        $totalPrice = new MonthExpenditureTotalPrice($items);
        $this->assertEquals(30, $totalPrice->count());
    }
    
    /**
     * @group MonthExpenditureTotalPrice
     */
    public function testAmountWithValidItemsAsFloat()
    {
        $items = array();
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 11.50);
        $items[] = $monthExpenditure;
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 20.35);
        $items[] = $monthExpenditure;
        
        $totalPrice = new MonthExpenditureTotalPrice($items);
        $this->assertEquals(31.85, $totalPrice->count());
    }
}