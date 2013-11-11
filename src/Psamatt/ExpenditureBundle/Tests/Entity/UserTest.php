<?php

namespace Psamatt\ExpenditureBundle\Tests\Twig\Extension;

use Psamatt\ExpenditureBundle\Entity\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testDisplayNameWithValidEmailAndFullname()
    {
        $user = new User;
        $user->update($username = 'John Doe', 'johndoe@gmail.com', 1);
        
        $this->assertEquals($username, $user->getDisplayName());
    }
    
    public function testDisplayNameWithValidEmailAndNoFullname()
    {
        $user = new User;
        $user->update(null, $emailAddress = 'johndoe@gmail.com', 1);

        $this->assertEquals($emailAddress, $user->getDisplayName());
    }
    
    public function testDisplayNameWithValidEmailAndEmptyFullname()
    {
        $user = new User;
        $user->update('', $emailAddress = 'johndoe@gmail.com', 1);
        
        $this->assertEquals($emailAddress, $user->getDisplayName());
    }
}

