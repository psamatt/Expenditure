<?php

namespace Psamatt\Pecunia\Application\ACL;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity()
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     */
    private $id;

    /** @ORM\Column(type="string") */
    private $email_address;

    /** @ORM\Column(type="string") */
    private $password;

    /** @ORM\Column(type="string") */
    private $salt;

    public static function fqcn()
    {
        return __CLASS__;
    }

    public function getId()
    {
        return $this->id;
    }

    /** @inheritDoc */
    public function getUsername()
    {
        return $this->email_address;
    }

    /** @inheritDoc */
    public function getSalt()
    {
        return $this->salt;
    }

    /** @inheritDoc */
    public function getPassword()
    {
        return $this->password;
    }

    /** @inheritDoc */
    public function getRoles()
    {
        return array('ROLE_ADMIN', 'ROLE_USER');
    }

    /** @inheritDoc */
    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email_address,
            $this->password,
            $this->salt,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email_address,
            $this->password,
            $this->salt
        ) = unserialize($serialized);
    }
}