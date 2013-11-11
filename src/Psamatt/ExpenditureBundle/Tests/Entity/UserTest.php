<?php

namespace Psamatt\ExpenditureBundle\Tests\Twig\Extension;

use Psamatt\ExpenditureBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testDisplayNameWithValidEmailAndFullname()
    {
        $user = new User;
        $user->setFullname($username = 'John Doe');
        $user->setEmailAddress('johndoe@gmail.com');
        
        $this->assertEquals($username, $user->getDisplayName());
    }
    
    public function testDisplayNameWithValidEmailAndNoFullname()
    {
        $user = new User;
        $user->setEmailAddress($emailAddress = 'johndoe@gmail.com');
        
        $this->assertEquals($emailAddress, $user->getDisplayName());
    }
    
    public function testDisplayNameWithValidEmailAndEmptyFullname()
    {
        $user = new User;
        $user->setFullname('');
        $user->setEmailAddress($emailAddress = 'johndoe@gmail.com');
        
        $this->assertEquals($emailAddress, $user->getDisplayName());
    }
}

