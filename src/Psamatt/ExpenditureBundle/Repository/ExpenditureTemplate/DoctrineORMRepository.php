<?php

namespace Psamatt\ExpenditureBundle\Repository\ExpenditureTemplate;

use Psamatt\ExpenditureBundle\Repository\DefaultDoctrineORMRepository;
use Psamatt\ExpenditureBundle\Repository\UserAwareInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctrineORMRepository extends DefaultDoctrineORMRepository implements UserAwareInterface
{   
    /**
     * {inheritdoc}
     *
     */
    protected $namespace = 'PsamattExpenditureBundle:MonthExpenditureTemplate';
    
    /**
     * {inheritdoc}
     */
    public function isOwnedByAdmin($item, $user)
    {
        if ($item->getUser()->getId() === $user->getId()) {
            return true;
        }
        
        throw new NotFoundHttpException("Page not found");
    }
}