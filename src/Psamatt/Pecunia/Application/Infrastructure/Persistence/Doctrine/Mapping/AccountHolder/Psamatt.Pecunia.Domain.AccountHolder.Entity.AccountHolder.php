<?php

$metadata->setTableName('account_holders');

$metadata->mapField([
    'id'        => true,
    'fieldName' => 'id',
    'type'      => 'integer',
]);
$metadata->mapField([
    'fieldName' => 'salutation',
    'type'      => 'string',
]);
$metadata->mapField([
    'fieldName' => 'firstname',
    'type'      => 'string',
]);
$metadata->mapField([
    'fieldName' => 'surname',
    'type'      => 'string',
]);
$metadata->mapField([
    'fieldName'  => 'emailAddress',
    'columnName' => 'email_address',
    'type'       => 'string',
]);
$metadata->mapField([
    'fieldName' => 'paidDay',
    'columnName'=> 'paid_day',
    'type'      => 'string',
]);
$metadata->mapField([
    'fieldName' => 'currency',
    'type'      => 'string',
]);
$metadata->mapField([
    'fieldName' => 'status',
    'type'      => 'integer',
]);