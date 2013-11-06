<?php

namespace Psamatt\ExpenditureBundle\Services;

use Psamatt\ExpenditureBundle\Repository\RepositoryInterface;
use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;
use Psamatt\ExpenditureBundle\ExpenditureEvents;
use Psamatt\ExpenditureBundle\Event\NoticeMessageEvent;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A service record to handle all month expenditure actions
 */
class MonthExpenditureService extends BasePageAction
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
     * Delete an expenditure
     *
     * @param MonthExpenditure $expenditure
     * @return boolean
     */
    public function delete(MonthExpenditure $expenditure)
    {
        $this->repository->delete($expenditure, true);
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new NoticeMessageEvent('Expenditure Deleted'));

        return true;
    }  
    
    /**
     * Save an expenditure
     *
     * @param MonthExpenditure $expenditure
     * @return boolean
     */
    public function save(MonthExpenditure $expenditure)
    {
        $this->repository->save($expenditure, true);
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new NoticeMessageEvent('Expenditure Saved'));
        
        return true;
    }
    
    /**
     * Save an update to how much has been paid
     *
     * @param MonthExpenditure $expenditure
     * @return boolean
     */
    public function savePayment(MonthExpenditure $expenditure)
    {
        $this->repository->save($expenditure, true);
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new NoticeMessageEvent('Expenditure set as ' . (!$expenditure->hasBeenPaid()? 'partially ':'') . 'paid'));
        
        return true;
    }

    /**
     * Find an expenditure item by its ID
     *
     * @param integer $id
     * @return object
     */
    public function findById($id)
    {
        $expenditureTemplate = $this->repository->findById($id);
        
        if (!$expenditureTemplate) {
            throw new ItemNotFoundException;
        }
        
        return $expenditureTemplate;
    }
    
    /**
     * Find all by a header
     *
     * @param MonthHeader $header
     * @return array[]
     */
    public function findAllByHeader(\Psamatt\ExpenditureBundle\Entity\MonthHeader $header)
    {
        return $this->repository->findBy(array('header' => $header), array('price' => 'DESC'));
    }
}