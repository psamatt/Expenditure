<?php

namespace Psamatt\ExpenditureBundle\Tests\Twig\Extension;

use Psamatt\ExpenditureBundle\Command\ChangePasswordCommand;

class ManagePasswordCommandTest extends \PHPUnit_Framework_TestCase
{
    private $managePasswordCommand;

    public function setUp()
    {
        // mock the dependencies
        $mockEncoderFactory = $this->getMockBuilder('Symfony\Component\Security\Core\Encoder\EncoderFactory')
            ->disableOriginalConstructor()
            ->getMock();
            
        $mockRespository = $this->getMockBuilder('Psamatt\ExpenditureBundle\Repository\RepositoryInterface')
            ->disableOriginalConstructor()
            ->getMock();
 
        $this->managePasswordCommand = new ChangePasswordCommand($mockEncoderFactory, $mockRespository);
    }
  
    public function testPasswordValidWithLessThanSixChars()
    {
        $this->managePasswordCommand->setPassword('aaaaa');
        $this->assertFalse($this->managePasswordCommand->isValidPassword());
    }
    
    public function testPasswordValidWithMoreThanSixCharsWithAllUpperCaseLetters()
    {
        $this->managePasswordCommand->setPassword('ABCDEFGHI');
        $this->assertFalse($this->managePasswordCommand->isValidPassword()); 
    }
    
    public function testPasswordValidWithMoreThanSixCharsWithMixedCaseLetters()
    {
        $this->managePasswordCommand->setPassword('AbCdEfGhI');
        $this->assertFalse($this->managePasswordCommand->isValidPassword());
    }
    
    public function testPasswordValidWithMoreThanSixCharsWithMixedCaseLettersAndNumbers()
    {
        $this->managePasswordCommand->setPassword('A1CdEf6hI');
        $this->assertTrue($this->managePasswordCommand->isValidPassword());
    }
}




