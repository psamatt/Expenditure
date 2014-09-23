<?php

use Doctrine\ORM\Mapping\ClassMetaDataInfo;

use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultOneOffMonthExpenditure;
use Psamatt\Pecunia\Domain\Expenditure\Entity\DefaultRecurringMonthExpenditure;

$metadata->setTableName('default_month_expenditures');

$metadata->setInheritanceType(ClassMetaDataInfo::INHERITANCE_TYPE_JOINED);
$metadata->setDiscriminatorColumn([
        'name' => 'type',
        'type' => 'string'
    ]);
$metadata->setDiscriminatorMap([
        'oneoff' => DefaultOneOffMonthExpenditure::fqcn(),
        'recurring' => DefaultRecurringMonthExpenditure::fqcn(),
    ]);

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
    'fieldName' => 'currency',
    'type'      => 'string',
]);
$metadata->mapField([
    'fieldName' => 'accountHolderId',
    'columnName'=> 'account_holder_id',
    'type'      => 'string',
]);
