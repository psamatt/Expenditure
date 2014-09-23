<?php

use Doctrine\ORM\Mapping\ClassMetaDataInfo;

$metadata->setTableName('month_expenditures');
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);

$metadata->mapField([
    'id'        => true,
    'fieldName' => 'id',
    'type'      => 'integer',
]);
$metadata->mapField([
    'fieldName' => 'calendarDate',
    'columnName' => 'calendar_date',
    'type'      => 'date',
]);
$metadata->mapField([
    'fieldName' => 'income',
    'columnName' => 'income',
    'type'      => 'float',
]);
$metadata->mapField([
    'fieldName' => 'currency',
    'columnName' => 'currency',
    'type'      => 'string',
]);
$metadata->mapField([
    'fieldName'   => 'accountHolderId',
    'columnName' => 'account_holder_id',
    'type'        => 'string',
]);
$metadata->mapField([
    'fieldName'   => 'totalPaid',
    'columnName' => 'total_paid',
    'type'        => 'float',
]);
$metadata->mapField([
    'fieldName'   => 'totalOutgoing',
    'columnName' => 'total_outgoing',
    'type'        => 'float',
]);

$metadata->mapOneToMany([
    'fieldName'    => 'expenditureItems',
    'targetEntity' => \Psamatt\Pecunia\Domain\Expenditure\Entity\CalendarMonthExpenditureItem::fqcn(),
    'mappedBy'     => 'monthExpenditure',
    'cascade'      => ['persist', 'remove'],
    'orphanRemoval'=> true,
    'orderBy'      => ['price' => 'DESC',],
]);