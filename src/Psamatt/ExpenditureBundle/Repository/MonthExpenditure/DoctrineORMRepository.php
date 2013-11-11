<?php

namespace Psamatt\ExpenditureBundle\Repository\MonthExpenditure;

use Psamatt\ExpenditureBundle\Repository\DefaultDoctrineORMRepository;

class DoctrineORMRepository extends DefaultDoctrineORMRepository
{   
    /**
     * {inheritdoc}
     *
     */
    protected $namespace = 'PsamattExpenditureBundle:MonthExpenditure';
}