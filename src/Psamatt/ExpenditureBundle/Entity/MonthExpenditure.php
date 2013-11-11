<?php

namespace Psamatt\ExpenditureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Used to store all monthly expenditures
 *
 * Psamatt\ExpenditureBundle\Entity\MonthExpenditure
 *
 * @ORM\Table(name="month_expenditure")
 * @ORM\Entity()
 */
class MonthExpenditure
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
     * Title of what the expenditure is
     *
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    protected $title;
    
    /**
     * The price of the expenditure
     *
     * @var float $price
     *
     * @ORM\Column(name="price", type="float")
     */
    protected $price;
    
    /**
     * The amount paid for the expenditure
     *
     * @var float $amount_paid
     *
     * @ORM\Column(name="amount_paid", type="float", nullable=true)
     */
    protected $amount_paid = 0;
    
    /**
     * Header ID
     *
     * @ORM\Column(name="header_id", type="integer")
     */
    protected $header_id;
    
    /**
     * @ORM\ManyToOne(targetEntity="MonthHeader", inversedBy="expenditures")
     * @ORM\JoinColumn(name="header_id", referencedColumnName="id")
     */
	protected $header;
    
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
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get price
     *
     * @return float 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get amount_paid
     *
     * @return float 
     */
    public function getAmountPaid()
    {
        return $this->amount_paid;
    }
    
    /**
     * Add money payment
     *
     * @param float $payment
     */
    public function addPayment($payment)
    {
        $this->amount_paid += $payment;
        
        if ($this->amount_paid > $this->price) {
            $this->amount_paid = $this->price;
        }
    }
    
    /**
     * Has this item been paid
     *
     * @return boolean
     */
    public function hasBeenPaid()
    {
        return $this->amount_paid >= $this->price;
    }
    
    /**
     * Get the header id
     *
     * @return integer
     */
    public function getHeaderId()
    {
        return $this->header_id;
    }

    /**
     * Set header
     *
     * @param \Psamatt\ExpenditureBundle\Entity\MonthHeader $header
     *
     * @return MonthExpenditure
     */
    public function setHeader(\Psamatt\ExpenditureBundle\Entity\MonthHeader $header = null)
    {
        $this->header = $header;
    }

    /**
     * Get header
     *
     * @return \Psamatt\ExpenditureBundle\Entity\MonthHeader 
     */
    public function getHeader()
    {
        return $this->header;
    }
    
    /**
     * Update
     *
     * @param string $title
     * @param string $price
     * @param \Psamatt\ExpenditureBundle\Entity\MonthHeader $header
     */
    public function update($title, $price, MonthHeader $header = null)
    {
        $this->title = $title;
        $this->price = $price;
        
        if ($header != null) {
            $this->header = $header;
        }
    }
}