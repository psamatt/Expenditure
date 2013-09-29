<?php

namespace Expenditure\Provider;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

use Doctrine\ORM\EntityManager;
use Expenditure\Entity\User;

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
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {inheritdoc}
     */
    public function loadUserByUsername($emailAddress)
    {
        $user = $this->em->getRepository('Expenditure:User')->findOneBy(array('email_address' => strtolower($emailAddress)));
    
        if ($user === null) {
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
        return $class === 'Expenditure\Entity\User';
    }
}