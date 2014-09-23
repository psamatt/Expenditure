<?php

use Doctrine\ORM\Mapping\ClassMetaDataInfo;

$metadata->setTableName('month_expenditure_items');

$metadata->mapField([
    'id'        => true,
    'fieldName' => 'id',
    'type'      => 'string',
]);
$metadata->mapField([
    'fieldName' => 'description',
    'type'      => 'string',
]);
$metadata->mapField([
    'fieldName' => 'price',
    'type'      => 'float',
]);
$metadata->mapField([
    'fieldName' => 'paidAmount',
    'columnName'=> 'paid_amount',
    'type'      => 'float',
]);
$metadata->mapManyToOne([
    'fieldName'    => 'monthExpenditure',
    'targetEntity' => \Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditure::fqcn(),
    'inversedBy'    => 'expenditureItems',
    'joinColumns'   => [
        [
            'name'                 => 'month_expenditure_id',
            'referencedColumnName' => 'id',
        ],
    ],
]);