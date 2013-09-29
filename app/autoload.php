<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = include __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerFile(__DIR__ . "/../vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");
AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

return $loader;