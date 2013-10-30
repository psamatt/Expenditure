<?php

namespace Psamatt\ExpenditureBundle\Tests\Twig\Extension;

use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;

class MonthExpenditureTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @group hasBeenPaid
     */
    public function testHasBeenPaidWithEqualAmount()
    {
        $expenditure = new MonthExpenditure;
        $expenditure->update('Foo', 100);
        $expenditure->addPayment(100);
        
        $this->assertTrue($expenditure->hasBeenPaid());
    }
    
    /**
     *
     * @group hasBeenPaid
     */
    public function testHasBeenPaidWithGreaterThanAmount()
    {
        $expenditure = new MonthExpenditure;
        $expenditure->update('Foo', 100);
        $expenditure->addPayment(200);

        $this->assertTrue($expenditure->hasBeenPaid());
    }
    
    /**
     *
     * @group hasBeenPaid
     */
    public function testHasBeenPaidWithLessThanAmount()
    {
        $expenditure = new MonthExpenditure;
        $expenditure->update('Foo', 100);
        $expenditure->addPayment(50);
        
        $this->assertFalse($expenditure->hasBeenPaid());
    }
    
    /**
     * Test
     */
    public function testAddPaymentOfGreaterThanPrice()
    {
        $expenditure = new MonthExpenditure;
        $expenditure->update('Foo', 100);
        $expenditure->addPayment(500);
        
        $this->assertEquals(100, $expenditure->getAmountPaid());
    }
}

