<?php

namespace Expenditure\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Used to store all monthly expenditure templates
 *
 * Expenditure\Entity\MonthExpenditureTemplate
 *
 * @ORM\Table(name="month_expenditure_template")
 * @ORM\Entity()
 */
class MonthExpenditureTemplate
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
     * Title of what the expenditure template is
     *
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    protected $title;
    
    /**
     * The price of the expenditure template
     *
     * @var float $price
     *
     * @ORM\Column(name="price", type="float")
     */
    protected $price;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="expenditureTemplates")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
	protected $user;
    
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
     * @return MonthExpenditureTemplate
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
     * @return MonthExpenditureTemplate
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
     * Set user
     *
     * @param \Expenditure\Entity\User $user
     *
     * @return MonthExpenditureTemplate
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
}