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
        $expenditure->setPrice(100);
        $expenditure->setAmountPaid(100);
        
        $this->assertTrue($expenditure->hasBeenPaid());
    }
    
    /**
     *
     * @group hasBeenPaid
     */
    public function testHasBeenPaidWithGreaterThanAmount()
    {
        $expenditure = new MonthExpenditure;
        $expenditure->setPrice(100);
        $expenditure->setAmountPaid(200);

        $this->assertTrue($expenditure->hasBeenPaid());
    }
    
    /**
     *
     * @group hasBeenPaid
     */
    public function testHasBeenPaidWithLessThanAmount()
    {
        $expenditure = new MonthExpenditure;
        $expenditure->setPrice(100);
        $expenditure->setAmountPaid(50);
        
        $this->assertFalse($expenditure->hasBeenPaid());
    }
}

