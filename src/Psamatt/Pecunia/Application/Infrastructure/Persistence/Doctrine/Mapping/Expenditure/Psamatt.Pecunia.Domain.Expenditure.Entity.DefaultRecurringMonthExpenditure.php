<?php

$metadata->setTableName('default_recurring_month_expenditures');

$metadata->mapField([
    'id'        => true,
    'fieldName' => 'id',
    'type'      => 'integer',
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
