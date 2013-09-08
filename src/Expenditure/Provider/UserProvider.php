<?php

namespace Expenditure\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Spot\Mapper;
use Expenditure\Model\User;

class UserProvider implements UserProviderInterface
{
    /**
     * Mapper
     * 
     * @access private
     * @var Mapper
     */
    private $mapper;

    /**
     * Constructor
     *
     * @param Mapper $mapper
     */
    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * {inheritdoc}
     */
    public function loadUserByUsername($emailAddress)
    {
        $user = $this->mapper->first('Expenditure\Model\User', array('email_address' => strtolower($emailAddress)));
    
        if ($user == false) {
            throw new UsernameNotFoundException(sprintf('Email "%s" does not exist.', $emailAddress));
        }

        return $user;
    }

    /**
     * {inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === 'Expenditure\Model\User';
    }
}