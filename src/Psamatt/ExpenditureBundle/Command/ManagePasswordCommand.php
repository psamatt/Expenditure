<?php

namespace Psamatt\ExpenditureBundle\Command;

use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

use Psamatt\ExpenditureBundle\Repository\RepositoryInterface;

/**
 * @DI\Service("managePassword.command")
 */
class ManagePasswordCommand
{    
    /**
     * Encoder Factory 
     *
     * @var EncoderFactory
     * @access private
     */
    private $encoderFactory;
    
    /**
     * User repository
     *
     * @var EncoderFactory
     * @access private
     */
    private $repository;
    
    private $user;
    private $password;
    
    /**
     * Constructor
     *
     * @DI\InjectParams({
     *     "encoderFactory" = @DI\Inject("security.encoder_factory"),
     *     "repository"     = @DI\Inject("user.repository")
     * })
     */
    public function __construct(EncoderFactory $encoderFactory, RepositoryInterface $repository)
    {
        $this->encoderFactory = $encoderFactory;
    }
    
    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    /**
     * Explicity for Form component
     *
     */
    public function getPassword()
    {
        return $this->password;
    }
    
    public function setUser(\Psamatt\ExpenditureBundle\Entity\User $user)
    {
        $this->user = $user;
    }
    
    /**
     * Get the encrypted password
     *
     * @param string $salt The users salt
     * @return string
     */
    public function getEncryptedPassword($salt)
    {
        $encoder = $this->encoderFactory->getEncoder($this->user);
        return $encoder->encodePassword($this->password, $salt);      
    }
     
     /**
     * @Assert\True(message = "Password must be 6 characters or more long, contain at least one number and an upper case and lower case letter")
     */
    public function isValidPassword()
    {    
        if (strlen($this->password) < 6 || null == $this->password) {
            return false;
        }
        
        if (!preg_match('/^(?=.*\d)(?=.*[a-zA-Z]).{6,}$/', $this->password)) {
            return false;
        }
        
        return true;
    }
}