<?php

namespace Context;

use Behat\Behat\Context\Step\Then;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Exception\ExpectationException;

class DatabaseManagementContext extends RawMinkContext
{
    /**
     * @param \Behat\Gherkin\Node\TableNode $table
     *
     * @Given /^(?:|the )following users are expenditure users$/
     *
     * @return null
     */
    public function followingUsersAreExpenditureUsers(TableNode $table)
    {
        $this->getPhabric()->insertFromTable('User', $table);
    }
    
    /**
     * @param \Behat\Gherkin\Node\TableNode $table
     *
     * @Given /^(?:|the )following savings are expenditure savings$/
     *
     * @return null
     */
    public function followingSavingsAreExpenditureSavings(TableNode $table)
    {
        $this->getPhabric()->insertFromTable('Saving', $table);
    }
    
    /**
     * @param \Behat\Gherkin\Node\TableNode $table
     *
     * @Given /^(?:|the )following default payments are expenditure default payments$/
     *
     * @return null
     */
    public function followingDefaultPaymentsAreExpenditureDefaultPayments(TableNode $table)
    {
        $this->getPhabric()->insertFromTable('DefaultPayment', $table);
    }
    
    /**
     * @param \Behat\Gherkin\Node\TableNode $table
     *
     * @Given /^(?:|the )following month headers are expenditure month headers$/
     *
     * @return null
     */
    public function followingMonthHeadersAreExpenditureMonthHeaders(TableNode $table)
    {
        $this->getPhabric()->insertFromTable('MonthHeader', $table);
    }
    
    /**
     * @param \Behat\Gherkin\Node\TableNode $table
     *
     * @Given /^(?:|the )following expenditures are expenditure$/
     *
     * @return null
     */
    public function followingExpendituresAreExpenditure(TableNode $table)
    {
        $this->getPhabric()->insertFromTable('MonthExpenditure', $table);
    }
    
    /**
     * @return \Phabric\Phabric
     */
    private function getPhabric()
    {
        return $this->getMainContext()->getPhabric();
    }
}