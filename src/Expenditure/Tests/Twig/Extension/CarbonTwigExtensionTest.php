<?php

namespace Expenditure\Tests\Twig\Extension;

use Expenditure\Twig\Extension\CarbonTwigExtension;

class CarbonTwigExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $twig;

    protected function setUp()
    {
        $this->twig = new CarbonTwigExtension;
    }
    
    public function testCarbonFilterWithValidDate()
    {
        $date = '2012-01-01';
    
        $carbonObject = $this->twig->carbonize($date);
        
        $this->assertInstanceOf('\Carbon\Carbon', $carbonObject);
        $this->assertEquals($date, $carbonObject->format('Y-m-d'));
    }
    
    public function testCarbonFilterWithValidTimestamp()
    {
        $timestamp = '@1325376000';
    
        $carbonObject = $this->twig->carbonize($timestamp);
        
        $this->assertInstanceOf('\Carbon\Carbon', $carbonObject);
        $this->assertEquals('2012-01-01', $carbonObject->format('Y-m-d'));
    }
}