<?php

namespace Expenditure\Model;

class MonthExpenditure extends \Spot\Entity
{
    protected static $_datasource = 'month_expenditure';

    public static function fields()
    {
        return array(
            'id' => array('type' => 'int', 'primary' => true, 'serial' => true),
            'title' => array('type' => 'string', 'required' => true),
            'price' => array('type' => 'float', 'required' => true),
            'amount_paid' => array('type' => 'float', 'required' => false),
            'header_id' => array('type' => 'int', 'required' => true),
        );
    }
}