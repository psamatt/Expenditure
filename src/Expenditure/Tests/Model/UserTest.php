<?php

namespace Expenditure\Tests\Twig\Extension;

use Expenditure\Model\User;

class UserTest extends \PHPUnit_Framework_TestCase
{   
    public function testDisplayNameWithValidEmailAndFullname()
    {
        $user = new User;
        $user->fullname = $username = 'John Doe';
        $user->email_address = 'johndoe@gmail.com';
        
        $this->assertEquals($username, $user->getDisplayName());
    }
    
    public function testDisplayNameWithValidEmailAndNoFullname()
    {
        $user = new User;
        $user->email_address = $emailAddress = 'johndoe@gmail.com';
        
        $this->assertEquals($emailAddress, $user->getDisplayName());
    }
    
    public function testDisplayNameWithValidEmailAndEmptyFullname()
    {
        $user = new User;
        $user->fullname = '';
        $user->email_address = $emailAddress = 'johndoe@gmail.com';
        
        $this->assertEquals($emailAddress, $user->getDisplayName());
    }
    
    public function testPasswordValidWithLessThanSixChars()
    {
        $user = new User;
        
        $this->assertFalse($user->isPasswordValid('aaaaa')); // less than 6 chars
    }
    
    public function testPasswordValidWithMoreThanSixCharsWithAllUpperCaseLetters()
    {
        $user = new User;
        
        $this->assertFalse($user->isPasswordValid('ABCDEFGHI')); // less than 6 chars
    }
    
    public function testPasswordValidWithMoreThanSixCharsWithMixedCaseLetters()
    {
        $user = new User;
        
        $this->assertFalse($user->isPasswordValid('AbCdEfGhI')); // less than 6 chars
    }
    
    public function testPasswordValidWithMoreThanSixCharsWithMixedCaseLettersAndNumbers()
    {
        $user = new User;
        
        $this->assertTrue($user->isPasswordValid('A1CdEf6hI')); // less than 6 chars
    }
}

