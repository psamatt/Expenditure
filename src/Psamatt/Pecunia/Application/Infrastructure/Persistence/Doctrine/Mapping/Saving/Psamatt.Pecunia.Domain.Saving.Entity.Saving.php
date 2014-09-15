<?php

use Doctrine\ORM\Mapping\ClassMetaDataInfo;

$metadata->setTableName('savings');
$metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_AUTO);

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
    'fieldName' => 'targetDate',
    'columnName'=> 'target_date',
    'type'      => 'date',
]);
$metadata->mapField([
    'fieldName' => 'targetAmount',
    'columnName'=> 'target_amount',
    'type'      => 'float',
]);
$metadata->mapField([
    'fieldName' => 'savedAmount',
    'columnName'=> 'saved_amount',
    'type'      => 'float',
]);
$metadata->mapField([
    'fieldName' => 'accountHolderId',
    'columnName'=> 'account_holder_id',
    'type'      => 'string',
]);