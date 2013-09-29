<?php

namespace Expenditure\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Used to store all monthly expenditures
 *
 * Expenditure\Entity\MonthExpenditure
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
     * @ORM\Column(name="amount_paid", type="float")
     */
    protected $amount_paid;
    
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
     * Set title
     *
     * @param string $title
     *
     * @return MonthExpenditure
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
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
     * Set price
     *
     * @param float $price
     *
     * @return MonthExpenditure
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
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
     * Set amount_paid
     *
     * @param float $amountPaid
     *
     * @return MonthExpenditure
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amount_paid = $amountPaid;

        return $this;
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
     * Set header
     *
     * @param \Expenditure\Entity\MonthHeader $header
     *
     * @return MonthExpenditure
     */
    public function setHeader(\Expenditure\Entity\MonthHeader $header = null)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return \Expenditure\Entity\MonthHeader 
     */
    public function getHeader()
    {
        return $this->header;
    }
}