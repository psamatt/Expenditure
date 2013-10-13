<?php

namespace Context;

use Behat\Behat\Context\BehatContext;
use Symfony\Component\HttpKernel\KernelInterface;

class PhabricContext extends BehatContext
{
    /**
     * array
     */
    private $parameters;
    
    /**
     * The Databse Connection.
     *
     * @var Doctrine\DBAL\Connection
     */
    private static $db;
    
    /**
     * @var \Phabric\Phabric $phabric
     */
    private $phabric = null;
    
    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return \Phabric\Phabric
     */
    public function getPhabric()
    {
        if (is_null($this->phabric)) {
            $this->initializePhabric();
        }

        return $this->phabric;
    }

    /**
     * @return null
     */
    private function initializePhabric()
    {
        $this->phabric = new \Phabric\Phabric($this->createPhabricDataSource());
        $this->addPhabricDataTransformations();
        $this->phabric->createEntitiesFromConfig($this->getPhabricParameters());
    }

    /**
     * @return \Phabric\Datasource\Doctrine
     */
    private function createPhabricDataSource()
    {
        $config = new \Doctrine\DBAL\Configuration();

        self::$db = \Doctrine\DBAL\DriverManager::getConnection(array(
                    'dbname' => $this->parameters['database']['dbname'],
                    'user' => $this->parameters['database']['username'],
                    'password' => $this->parameters['database']['password'],
                    'host' => $this->parameters['database']['host'],
                    'driver' => $this->parameters['database']['driver'],
                ));
        
        $datasource = new \Phabric\Datasource\Doctrine(self::$db, $this->getPhabricParameters());

        return $datasource;
    }

    /**
     * @return null
     */
    private function addPhabricDataTransformations()
    {
        $this->phabric->addDataTransformation(
            'TEXTTOMYSQLDATE', function($date) {
                $date = $date != 'NOW'? \DateTime::createFromFormat('dS M Y', $date): new \DateTime;

                return $date->format('Y-m-d H:i:s');
            }
        );
        $this->phabric->addDataTransformation(
            'HEADERLOOKUP', function($headerLogin, $bus) {
                $header = $bus->getEntity('MonthHeader');

                $transformer = $bus->getDataTransformation('TEXTTOMYSQLDATE');
                $date = $transformer($headerLogin);

                return $header->getNamedItemId($date);
            }
        );
    }

    /**
     * @return array
     */
    private function getPhabricParameters()
    {
        return array(
            'User' => array(
                'tableName' => 'users',
                'primaryKey' => 'id',
                'nameCol' => 'email_address',
                'nameTransformations' => array(
                    'Email Address' => 'email_address',
                    'Full name' => 'fullname',
                    'Paid Day' => 'paid_day',
                    'Salt' => 'salt',
                    'Password' => 'password',
                    'Status' => 'status',
                )
            ),
            'Saving' => array(
                'tableName' => 'savings',
                'primaryKey' => 'id',
                'nameCol' => 'title',
                'nameTransformations' => array(
                    'ID' => 'id',
                    'Title' => 'title',
                    'User' => 'user_id',
                    'Target Date' => 'target_date',
                    'Target Amount' => 'target_amount',
                    'Saved Amount' => 'saved_amount',
                ),
                'dataTransformations' => array(
                    'target_date' => 'TEXTTOMYSQLDATE'
                )
            ),
            'DefaultPayment' => array(
                'tableName' => 'month_expenditure_template',
                'primaryKey' => 'id',
                'nameCol' => 'title',
                'nameTransformations' => array(
                    'ID' => 'id',
                    'Title' => 'title',
                    'User' => 'user_id',
                    'Price' => 'price',
                ),
            ),
            'MonthHeader' => array(
                'tableName' => 'month_header',
                'primaryKey' => 'id',
                'nameCol' => 'calendar_date',
                'nameTransformations' => array(
                    'ID' => 'id',
                    'User' => 'user_id',
                    'Calendar Date' => 'calendar_date',
                    'Month Income' => 'month_income',
                ),
                'dataTransformations' => array(
                    'calendar_date' => 'TEXTTOMYSQLDATE',
                )
            ),
            'MonthExpenditure' => array(
                'tableName' => 'month_expenditure',
                'primaryKey' => 'id',
                'nameCol' => 'title',
                'nameTransformations' => array(
                    'ID' => 'id',
                    'Header' => 'header_id',
                    'Title' => 'title',
                    'Price' => 'price',
                    'Amount Paid' => 'amount_paid',
                ),
                'dataTransformations' => array(
                    'header_id' => 'HEADERLOOKUP'
                )
            ),
        );
    }
}