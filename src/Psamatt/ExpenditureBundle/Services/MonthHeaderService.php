<?php

namespace Psamatt\ExpenditureBundle\Services;

use Psamatt\ExpenditureBundle\Repository\RepositoryInterface;
use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;
use Psamatt\ExpenditureBundle\ExpenditureEvents;
use Psamatt\ExpenditureBundle\Event\MessageEvent;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A service record to handle all month headers
 */
class MonthHeaderService extends BasePageAction
{
    /**
     *
     */
    protected $repository;
    
    /**
     * Constructor
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Find an month header item by its ID
     *
     * @param integer $id
     * @return object
     */
    public function findById($id)
    {
        $monthHeader = $this->repository->findById($id);
        
        if (!$monthHeader) {
            throw new ItemNotFoundException;
        }
        
        return $monthHeader;
    }
}