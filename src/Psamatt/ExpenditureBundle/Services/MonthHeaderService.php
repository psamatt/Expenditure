<?php

namespace Psamatt\ExpenditureBundle\Services;

use Psamatt\ExpenditureBundle\Repository\RepositoryInterface;
use Psamatt\ExpenditureBundle\Repository\Exception\ItemNotFoundException;
use Psamatt\ExpenditureBundle\Entity\MonthHeader;
use Psamatt\ExpenditureBundle\ExpenditureEvents;
use Psamatt\ExpenditureBundle\Event\NoticeMessageEvent;

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
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new NoticeMessageEvent($msg));
        
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
    
    /**
     * Find a month header by date and user
     *
     * @param \DateTime $date The date
     * @param User $user 
     * @return MonthHeader
     */
    public function findByDateAndUser(\DateTime $date, \Psamatt\ExpenditureBundle\Entity\User $user)
    {
        $date->setTime(0,0,0);
    
        $monthHeader = $this->repository->findOneBy(array('calendar_date' => $date, 'user' => $user));
    
        if ($monthHeader == null) {
            throw new ItemNotFoundException;
        }
        
        return $monthHeader;
    }
    
    /**
     * Find the latest header by a user
     *
     * @param User $user
     * @return MonthHeader
     */
    public function findLatestByUser(\Psamatt\ExpenditureBundle\Entity\User $user)
    {
        $monthHeader = $this->repository->findLatest(array('user' => $user));

        if ($monthHeader == null) {
            throw new ItemNotFoundException;
        }
        
        return $monthHeader;
    }
    
    /**
     * Find all the user 
     *
     * @param User $user
     * @return array[]
     */
    public function findAllByUser(\Psamatt\ExpenditureBundle\Entity\User $user)
    {
        return $this->repository->findBy(array('user' => $user), array('calendar_date' => 'DESC'));
    }
}