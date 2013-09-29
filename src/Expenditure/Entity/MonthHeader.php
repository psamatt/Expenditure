<?php

namespace Expenditure\Entity;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * Used to store all monthly headers throughout the system
 *
 * Expenditure\Entity\MonthHeader
 *
 * @ORM\Table(name="month_header")
 * @ORM\Entity()
 */
class MonthHeader
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The calendar date of the month
     *
     * @var DateTime $calendar_date
     *
     * @ORM\Column(name="calendar_date", type="date", nullable=true)
     */
    protected $calendar_date;
    
    /**
     * The monthly income for this month
     *
     * @var float $month_amount
     *
     * @ORM\Column(name="month_income", type="float")
     */
    protected $month_income;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="monthHeaders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
	protected $user;
	
	/**
     * @ORM\OneToMany(targetEntity="MonthExpenditure", mappedBy="header", cascade={"all"})
     * @ORM\OrderBy()
     */
    protected $expenditures;
    
    public function __construct()
    {
    	$this->expenditures = new ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set calendar_date
     *
     * @param \DateTime $calendarDate
     *
     * @return MonthHeader
     */
    public function setCalendarDate($calendarDate)
    {
        $this->calendar_date = $calendarDate;

        return $this;
    }

    /**
     * Get calendar_date
     *
     * @return \DateTime 
     */
    public function getCalendarDate()
    {
        return $this->calendar_date;
    }

    /**
     * Set month_income
     *
     * @param float $monthIncome
     *
     * @return MonthHeader
     */
    public function setMonthIncome($monthIncome)
    {
        $this->month_income = $monthIncome;

        return $this;
    }

    /**
     * Get month_income
     *
     * @return float 
     */
    public function getMonthIncome()
    {
        return $this->month_income;
    }

    /**
     * Set user
     *
     * @param \Expenditure\Entity\User $user
     *
     * @return MonthHeader
     */
    public function setUser(\Expenditure\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Expenditure\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add expenditures
     *
     * @param \Expenditure\Entity\MonthExpenditure $expenditure
     *
     * @return MonthHeader
     */
    public function addExpenditure(\Expenditure\Entity\MonthExpenditure $expenditure)
    {
        $this->expenditures[] = $expenditure;
        
        $expenditure->setHeader($this);

        return $this;
    }

    /**
     * Remove expenditures
     *
     * @param \Expenditure\Entity\MonthExpenditure $expenditure
     */
    public function removeExpenditure(\Expenditure\Entity\MonthExpenditure $expenditure)
    {
        $this->expenditures->removeElement($expenditure);
    }

    /**
     * Get expenditures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExpenditures()
    {
        return $this->expenditures;
    }
}