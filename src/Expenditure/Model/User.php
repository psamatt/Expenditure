<?php

namespace Expenditure\Model;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class User extends \Spot\Entity implements AdvancedUserInterface
{
    protected static $_datasource = 'users';

    public static function fields()
    {
        return array(
            'id' => array('type' => 'int', 'primary' => true, 'serial' => true),
            'fullname' => array('type' => 'string', 'required' => false),
            'email_address' => array('type' => 'string', 'required' => true, 'email' => true),
            'salt' => array('type' => 'string', 'required' => true),
            'password' => array('type' => 'string', 'required' => true),
            'paid_day' => array('type' => 'integer', 'required' => false, 'min' => 1, 'max' => 31),
            'status' => array('type' => 'integer', 'required' => true),
        );
    }
    
    public static function relations()
    {
        return array(
            'savings' => array(
                'type' => 'HasMany',
                'entity' => 'Expenditure\Model\Saving',
                'where' => array('user_id' => ':entity.id'),
                'order' => array('target_date' => 'ASC')
            ),
            'monthHeaders' => array(
                'type' => 'HasMany',
                'entity' => 'Expenditure\Model\MonthHeader',
                'where' => array('user_id' => ':entity.id'),
                'order' => array('calendar_date' => 'DESC')
            ),
            'monthExpenditureTemplates' => array(
                'type' => 'HasMany',
                'entity' => 'Expenditure\Model\MonthExpenditureTemplate',
                'where' => array('user_id' => ':entity.id'),
                'order' => array('price' => 'DESC')
            )
        );
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
     * {@inheritdoc}
     */
    public function getRoles()
    {
        return array('ROLE_ADMIN');
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
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