<?php

namespace Psamatt\ExpenditureBundle\Tests\Twig\Extension;

use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;
use Psamatt\ExpenditureBundle\Library\MonthExpenditureTotalPaid;

class MonthExpenditureTotalPaidTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @group MonthExpenditureTotalPaid
     */
    public function testAmountWithValidItemsAsInteger()
    {
        $items = array();
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 10);
        $monthExpenditure->addPayment(5);
        $items[] = $monthExpenditure;
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 20);
        $monthExpenditure->addPayment(12);
        $items[] = $monthExpenditure;
        
        $totalPrice = new MonthExpenditureTotalPaid($items);
        $this->assertEquals(17, $totalPrice->count());
    }
    
    /**
     * @group MonthExpenditureTotalPaid 
     */
    public function testAmountWithParticularValidItemsAsInteger()
    {
        $items = array();
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 10);
        $monthExpenditure->addPayment(5);
        $items[] = $monthExpenditure;
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 20);
        $monthExpenditure->addPayment(12);
        $items[] = $monthExpenditure;
        
        $items[] = 10;
        
        $totalPrice = new MonthExpenditureTotalPaid($items);
        $this->assertEquals(17, $totalPrice->count());
    }
    
    /**
     * @group MonthExpenditureTotalPaid
     */
    public function testAmountWithValidItemsAsFloat()
    {
        $items = array();
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 11.50);
        $monthExpenditure->addPayment(5.50);
        $items[] = $monthExpenditure;
        
        $monthExpenditure = new MonthExpenditure;
        $monthExpenditure->update('Foo', 20.35);
        $monthExpenditure->addPayment(8.35);
        $items[] = $monthExpenditure;
        
        $totalPrice = new MonthExpenditureTotalPaid($items);
        $this->assertEquals(13.85, $totalPrice->count());
    }
}