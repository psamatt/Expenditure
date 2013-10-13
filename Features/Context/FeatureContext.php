<?php

namespace Context;

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Step;
    
use Behat\MinkExtension\Context\MinkContext;
use Behat\CommonContexts\SymfonyDoctrineContext;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->useContext('db_management', new DatabaseManagementContext());

        $this->useContext('phabric', new PhabricContext($parameters));        
        $this->useContext('symfony_doctrine_context', new SymfonyDoctrineContext);
    }
    
    /**
     * @Given /^(?:|I )am logged in as "([^"]*)" with "([^"]*)"$/
     */
    public function iAmLoggedInAsAUser($username, $password)
    {    
        return array(
            new Step\When('I am on "/login"'),
            new Step\When('I fill in "_username" with "' . $username . '"'),
            new Step\When('I fill in "_password" with "' . $password . '"'),
            new Step\When('I press "Sign in"'),
            new Step\Then("I should be on \"/admin\""),
        );
    }
    
    /**
     * When I follow a link found by a CSS selector
     *
     * @When /^(?:|I )follow css "(?P<link>(?:[^"]|\\")*)"$/
     */
    public function clickLinkByCSSSelector($link)
    {
        $link = $this->fixStepArgument($link);
        
        $link = $this->getSession()->getPage()->find('css', $link);

        if (null === $link) {
            throw new ElementNotFoundException(
                $this->getSession(), 'link', 'id|title|alt|text', $locator
            );
        }

        $link->click();
    }
    
    /**
     * Checks, that element with specified CSS contains todays formatted specified HTML.
     *
     * @Then /^the "(?P<element>[^"]*)" element should todays "(?P<value>(?:[^"]|\\")*)"$/
     */
    public function assertElementContains($element, $value)
    {
        $today = new \DateTime;
    
        switch ($value) {
            case 'text_month':
                $today->format('a'); //check
                break;
            case 'year':
                $today->format('Y'); //check
                break;
            case 'text_day':
                $today->format('d'); //check
                break;
            case 'numeric_day':
                $today->format('j'); //check
                break;
        }
        /*
        
        
        return array(
            new Step\When('I am on "/login"'),
            new Step\When('I fill in "_username" with "' . $username . '"'),
            new Step\When('I fill in "_password" with "' . $password . '"'),
            new Step\When('I press "Sign in"'),
            new Step\Then("I should be on \"/admin\""),
        );
    
        $this->assertSession()->elementContains('css', $element, $this->fixStepArgument($value));
        */
    }
    
    /**
    * @When /^(?:|I )post to "([^"]*)"$/
    */
    public function confirmPopup($uri)
    {
        $this->getSession()->getDriver()->getWebDriverSession()->open($this->locatePath($uri));
    }
    
    /**
     * @return \Phabric\Phabric
     */
    public function getPhabric()
    {
        return $this->getSubcontext('phabric')->getPhabric();
    }
}
