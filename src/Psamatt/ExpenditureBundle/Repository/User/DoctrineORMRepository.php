<?php

namespace Psamatt\ExpenditureBundle\Repository\User;

use Psamatt\ExpenditureBundle\Repository\DefaultDoctrineORMRepository;
use Psamatt\ExpenditureBundle\Repository\UserAwareInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctrineORMRepository extends DefaultDoctrineORMRepository
{   
    /**
     * {inheritdoc}
     *
     */
    protected $namespace = 'PsamattExpenditureBundle:User';
}