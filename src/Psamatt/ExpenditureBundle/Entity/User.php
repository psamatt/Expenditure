<?php

namespace Psamatt\ExpenditureBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Used to store all users throughout the system
 *
 * Psamatt\ExpenditureBundle\Entity\User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity()
 */
class User implements AdvancedUserInterface
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
     * Email address of user
     *
     * @var string $email_address
     *
     * @ORM\Column(name="email_address", type="string")
     */
    protected $email_address;

    /**
     * Full name of user
     *
     * @var string $fullname
     *
     * @ORM\Column(name="fullname", type="string", nullable=true)
     */
    protected $fullname;
    
    /**
     * The day of the month the user gets paid
     *
     * @var string $paid_day
     * @ORM\Column(name="paid_day", type="integer", nullable=true)
     */
    protected $paid_day;
    
    /**
     * The salt used in encrypting the users password
     *
     * @var integer $salt
     *
     * @ORM\Column(name="salt", type="string")
     */
    protected $salt;
    
    /**
     * The users password
     *
     * @var string $password
     *
     * @ORM\Column(name="password", type="string")
     */
    protected $password;
    
    /**
     * The status of the user
     *
     * @var string $status
     *
     * @ORM\Column(name="status", type="integer")
     */
    protected $status = 1;
	
	/**
     * @ORM\OneToMany(targetEntity="Saving", mappedBy="user", cascade={"all"})
     * @ORM\OrderBy()
     */
    protected $savings;
    
    /**
     * @ORM\OneToMany(targetEntity="MonthHeader", mappedBy="user", cascade={"all"})
     * @ORM\OrderBy({"calendar_date" = "DESC"})
     */
    protected $monthHeaders;
    
    /**
     * @ORM\OneToMany(targetEntity="MonthExpenditureTemplate", mappedBy="user", cascade={"all"})
     * @ORM\OrderBy()
     */
    protected $expenditureTemplates;
    
	public function __construct()
    {
    	$this->savings = new ArrayCollection();
    	$this->monthHeaders = new ArrayCollection();
    	$this->expenditureTemplates = new ArrayCollection();
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
     * Set email_address
     *
     * @param string $emailAddress
     *
     * @return User
     */
    public function setEmailAddress($emailAddress)
    {
        $this->email_address = $emailAddress;

        return $this;
    }

    /**
     * Get email_address
     *
     * @return string 
     */
    public function getEmailAddress()
    {
        return $this->email_address;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return User
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }
    
    /**
     * Get the display name
     *
     * @return string
     */
    public function getDisplayName()
    {
        if (strlen($this->fullname) == 0) {
            return $this->email_address;
        }
        
        return $this->fullname;
    }
    
    /**
     * Is the password valid
     *
     * @param string $password
     * @return boolean
     */
    public function isPasswordValid($password)
    {
        if (strlen($password) < 6) {
            return false;
        }
        
        if (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z]).{6,}$/', $password)) {
            return false;
        }
        
        return true;
    }

    /**
     * Set paid_day
     *
     * @param integer $paidDay
     *
     * @return User
     */
    public function setPaidDay($paidDay)
    {
        $this->paid_day = $paidDay;

        return $this;
    }

    /**
     * Get paid_day
     *
     * @return integer 
     */
    public function getPaidDay()
    {
        return $this->paid_day;
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return User
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }

    /**
     * Add savings
     *
     * @param \Psamatt\ExpenditureBundle\Entity\Saving $savings
     *
     * @return User
     */
    public function addSaving(\Psamatt\ExpenditureBundle\Entity\Saving $savings)
    {
        $this->savings[] = $savings;

        return $this;
    }

    /**
     * Remove savings
     *
     * @param \Psamatt\ExpenditureBundle\Entity\Saving $savings
     */
    public function removeSaving(\Psamatt\ExpenditureBundle\Entity\Saving $savings)
    {
        $this->savings->removeElement($savings);
    }

    /**
     * Get savings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSavings()
    {
        return $this->savings;
    }

    /**
     * Add monthHeaders
     *
     * @param \Psamatt\ExpenditureBundle\Entity\MonthHeader $monthHeaders
     *
     * @return User
     */
    public function addMonthHeader(\Psamatt\ExpenditureBundle\Entity\MonthHeader $monthHeaders)
    {
        $this->monthHeaders[] = $monthHeaders;

        return $this;
    }

    /**
     * Remove monthHeaders
     *
     * @param \Psamatt\ExpenditureBundle\Entity\MonthHeader $monthHeaders
     */
    public function removeMonthHeader(\Psamatt\ExpenditureBundle\Entity\MonthHeader $monthHeaders)
    {
        $this->monthHeaders->removeElement($monthHeaders);
    }

    /**
     * Get monthHeaders
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMonthHeaders()
    {
        return $this->monthHeaders;
    }

    /**
     * Add expenditureTemplates
     *
     * @param \Psamatt\ExpenditureBundle\Entity\MonthExpenditureTemplate $expenditureTemplates
     *
     * @return User
     */
    public function addExpenditureTemplate(\Psamatt\ExpenditureBundle\Entity\MonthExpenditureTemplate $expenditureTemplates)
    {
        $this->expenditureTemplates[] = $expenditureTemplates;

        return $this;
    }

    /**
     * Remove expenditureTemplates
     *
     * @param \Psamatt\ExpenditureBundle\Entity\MonthExpenditureTemplate $expenditureTemplates
     */
    public function removeExpenditureTemplate(\Psamatt\ExpenditureBundle\Entity\MonthExpenditureTemplate $expenditureTemplates)
    {
        $this->expenditureTemplates->removeElement($expenditureTemplates);
    }

    /**
     * Get expenditureTemplates
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExpenditureTemplates()
    {
        return $this->expenditureTemplates;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->email_address;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}