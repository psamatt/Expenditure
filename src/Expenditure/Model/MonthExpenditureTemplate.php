<?php

namespace Expenditure\Model;

class MonthExpenditureTemplate extends \Spot\Entity
{
    protected static $_datasource = 'month_expenditure_template';

    public static function fields()
    {
        return array(
            'id' => array('type' => 'int', 'primary' => true, 'serial' => true),
            'title' => array('type' => 'text', 'required' => true),
            'price' => array('type' => 'float', 'required' => true),
        );
    }
}