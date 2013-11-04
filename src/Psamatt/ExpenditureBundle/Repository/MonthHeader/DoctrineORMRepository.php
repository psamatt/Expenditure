<?php

namespace Psamatt\ExpenditureBundle\Repository\MonthHeader;

use Psamatt\ExpenditureBundle\Repository\DefaultDoctrineORMRepository;
use Psamatt\ExpenditureBundle\Repository\UserAwareInterface;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DoctrineORMRepository extends DefaultDoctrineORMRepository implements UserAwareInterface
{   
    /**
     * {inheritdoc}
     *
     */
    protected $namespace = 'PsamattExpenditureBundle:MonthHeader';
    
    /**
     * Find latest month headers
     *
     * @param array $clauses The clauses
     * @return MonthHeader
     */
    public function findLatest($clauses = array())
    {
        $items = $this->getRepository()->findBy($clauses, array('calendar_date' => 'DESC'), 1);
        
        if (count($items) > 0) {
            return $items[0];
        }
        
        return null;
    }
    
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