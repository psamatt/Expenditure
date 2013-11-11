<?php

namespace Psamatt\ExpenditureBundle\Repository\Exception;

/**
 * Exception thrown when no namespace has been found for a Doctrine ORM Repository function
 *
 */
class NamespaceNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('No namespace was defined.');
    }
}