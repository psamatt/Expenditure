<?php

namespace Psamatt\ExpenditureBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Used to store all monthly expenditure templates
 *
 * Psamatt\ExpenditureBundle\Entity\MonthExpenditureTemplate
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
     * Get user
     *
     * @return \Psamatt\ExpenditureBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Update a template
     * 
     * @param string $title
     * @param string $price
     * @param User $user
     */
    public function update($title, $price, User $user = null)
    {
        $this->title = $title;
        $this->price = $price;
        
        if ($user != null) {
            $this->user = $user;
        }
    }
}