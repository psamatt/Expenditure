<?php

namespace Expenditure\Model;

class MonthHeader extends \Spot\Entity
{
    protected static $_datasource = 'month_header';

    public static function fields()
    {
        return array(
            'id' => array('type' => 'int', 'primary' => true, 'serial' => true),
            'calendar_date' => array('type' => 'datetime', 'required' => true),
            'month_income' => array('type' => 'float', 'required' => true),
            'user_id' => array('type' => 'integer', 'required' => true),
        );
    }
}