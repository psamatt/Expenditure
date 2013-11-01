<?php

namespace Psamatt\ExpenditureBundle\Services;

use Psamatt\ExpenditureBundle\Repository\RepositoryInterface;
use Psamatt\ExpenditureBundle\Entity\MonthHeader;
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
     *
     */
    protected $dispatcher;
    
    /**
     * Constructor
     */
    public function __construct(RepositoryInterface $repository, EventDispatcher $dispatcher)
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }
    
    /**
     * Save a month header
     *
     * @param MonthHeader $header
     * @return boolean
     */
    public function save(MonthHeader $header)
    {
        $new = false;
    
        if ($header->getId() == null) {
            $new = true;
        }
    
        $this->repository->save($header, true);
        
        
        $msg = $new? $header->getCalendarDate()->format('F Y') . ' Created': 'Header Saved';
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new MessageEvent($msg));
        
        return true;
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