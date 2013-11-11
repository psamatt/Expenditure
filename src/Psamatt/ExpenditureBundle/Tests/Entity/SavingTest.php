<?php

namespace Psamatt\ExpenditureBundle\Tests\Twig\Extension;

use Psamatt\ExpenditureBundle\Entity\Saving;

use Psamatt\Expenditure\Library\SavingMoney;

class SavingTest extends \PHPUnit_Framework_TestCase
{
    /**
     * 
     * @group isOverTargetAmount
     */
    public function testIsOverTargetAmountWithLessThanAmount()
    {
        $saving = new Saving;
        $saving->update('Test', null, 100, 0);
        
        $this->assertFalse($saving->isOverTargetAmount(10));
    }
    
    /**
     * 
     * @group isOverTargetAmount
     */
    public function testIsOverTargetAmountWithGreaterThanAmount()
    {
        $saving = new Saving;
        $saving->update('Test', null, 100, 0);
        
        $this->assertTrue($saving->isOverTargetAmount(999));
    }
    
    /**
     * 
     * @group isOverTargetAmount
     */
    public function testIsOverTargetAmountWithEqualAmount()
    {
        $saving = new Saving;
        $saving->update('Test', null, 100, 0);
        
        $this->assertFalse($saving->isOverTargetAmount(100));
    }
    
    /**
     * 
     * @group isOverTargetAmount
     */
    public function testIsOverTargetAmountWithFloatGreatherThanAmount()
    {
        $saving = new Saving;
        $saving->update('Test', null, 100, 0);
        
        $this->assertTrue($saving->isOverTargetAmount(100.10));
    }

    /**
     *
     * @group addMoney
     */
    public function testSavingAddMoney()
    {
        $saving = new Saving;
        $saving->update('Test', null, 100, 0);
        
        $saving->addMoney(new SavingMoney(10));
        
        $this->assertEquals(10, $saving->getSavedAmount());
    }
    
    /**
     *
     * @group isGoalReached
     */
    public function testGoalOverReached()
    {
        $saving = new Saving;
        $saving->update('Test', null, 1000, 1500);
        
        $this->assertTrue($saving->isGoalReached());
    }
    
    /**
     *
     * @group isGoalReached
     */
    public function testGoalExactReached()
    {
        $saving = new Saving;
        $saving->update('Test', null, 1000, 1000);
        
        $this->assertTrue($saving->isGoalReached());
    }
    
    /**
     *
     * @group isGoalReached
     */
    public function testGoalNotReached()
    {
        $saving = new Saving;
        $saving->update('Test', null, 1000, 10);
        
        $this->assertFalse($saving->isGoalReached());
    }
    
    /**
     *
     * @group isGoalReached
     */
    public function testGoalNotReachedByPence()
    {
        $saving = new Saving;
        $saving->update('Test', null, 1000, 999.99);
        
        $this->assertFalse($saving->isGoalReached());
    }
    
    /**
     *
     * @group getPercentProgress
     */
    public function testPercentProgressOfExactlyHalfSaved()
    {
        $saving = new Saving;
        $saving->update('Test', null, 1000, 500);
        
        $this->assertEquals(50, $saving->getPercentProgress());
    }
    
    /**
     *
     * @group getPercentProgress
     */
    public function testPercentProgressOfDecimalSavedPercentProgress()
    {
        $saving = new Saving;
        $saving->update('Test', null, 1000, 498);
        
        $this->assertEquals(50, $saving->getPercentProgress());
    }
    
    /**
     *
     * @group getAmountRemaining
     */
    public function testSavingAmountRemaining()
    {
        $saving = new Saving;
        $saving->update('Test', null, 1000, 400);
        
        $this->assertEquals(600, $saving->getAmountRemaining());
    }
    
    /**
     *
     * @group getNumberOfPayDaysRemaining
     */
    public function testNumberOfPayDaysRemainingOfFiveMonthsDifferencePayDayAfterStartDay()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-01'), 0, 0);

        $this->assertEquals(5, $saving->getNumberOfPayDaysRemaining(15, new \DateTime('2014-08-01')));
    }
    
    /**
     *
     * @group getNumberOfPayDaysRemaining
     */
    public function testNumberOfPayDaysRemainingOfFiveMonthsDifferencePayDayBeforeStartDay()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-01'), 0, 0);

        $this->assertEquals(4, $saving->getNumberOfPayDaysRemaining(15, new \DateTime('2014-08-20')));
    }
    
    /**
     *
     * @group getNumberOfPayDaysRemaining
     */
    public function testNumberOfPayDaysRemainingOfFiveMonthsDifferencePayDayIsStartDay()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-01'), 0, 0);

        $this->assertEquals(6, $saving->getNumberOfPayDaysRemaining(1, new \DateTime('2014-08-01')));
    }
    
    /**
     *
     * @group getNumberOfPayDaysRemaining
     */
    public function testNumberOfPayDaysRemainingOfOneMonthDifferencePayDayAfterStartDay()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-02-01'), 0, 0);

        $this->assertEquals(1, $saving->getNumberOfPayDaysRemaining(10, new \DateTime('2015-01-01')));
    }
    
    /**
     *
     * @group getNumberOfPayDaysRemaining
     */
    public function testNumberOfPayDaysRemainingOfLessThanOneMonthDifferencePayDayIsStartDay()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-30'), 0, 0);

        $this->assertEquals(1, $saving->getNumberOfPayDaysRemaining(1, new \DateTime('2015-01-01')));
    }
    
    /**
     *
     * @group getNumberOfPayDaysRemaining
     */
    public function testNumberOfPayDaysRemainingOfLessThanOneMonthDifferencePayDayAfterStartDay()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-30'), 0, 0);

        $this->assertEquals(1, $saving->getNumberOfPayDaysRemaining(10, new \DateTime('2015-01-01')));
    }
    
    /**
     *
     * @group getNumberOfPayDaysRemaining
     */
    public function testNumberOfPayDaysRemainingOfLessThanOneMonthDifferencePayDayBeforeStartDay()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-30'), 0, 0);

        $this->assertEquals(0, $saving->getNumberOfPayDaysRemaining(10, new \DateTime('2015-01-20')));
    }
    
    /**
     *
     * @group getNumberOfPayDaysRemaining
     */
    public function testNumberOfPayDaysRemainingOfLessThanOneMonthDifferencePayDayAfterEndDay()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-10'), 0, 0);

        $this->assertEquals(0, $saving->getNumberOfPayDaysRemaining(20, new \DateTime('2015-01-01')));
    }
    
    /**
     *
     * @group getAmountPerMonth
     */
    public function testAmounthPerMonthWithEightMonthsOf800Remaining()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-01'), 1000, 200);
        
        $this->assertEquals(100, $saving->getAmountPerMonth(10, new \DateTime('2014-05-01')));
    }
    
    /**
     *
     * @group getAmountPerMonth
     */
    public function testAmounthPerMonthWithTwoDecimalPlacesByDefault()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-01'), 1121, 200);
        
        $this->assertEquals(115.13, $saving->getAmountPerMonth(10, new \DateTime('2014-05-01')));
    }
    
    /**
     *
     * @group getAmountPerMonth
     */
    public function testAmounthPerMonthWithoutTwoDecimalPlaces()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-01'), 1121, 200);
        
        $this->assertEquals(115.125, $saving->getAmountPerMonth(10, new \DateTime('2014-05-01'), false));
    }
    
    /**
     *
     * @group getAmountPerMonth
     */
    public function testAmounthPerMonthWithTwoDecimalPlacesByTrue()
    {
        $saving = new Saving;
        $saving->update('Test', new \DateTime('2015-01-01'), 1121, 200);
        
        $this->assertEquals(115.13, $saving->getAmountPerMonth(10, new \DateTime('2014-05-01'), true));
    }
}