<?php

namespace Psamatt\ExpenditureBundle\Services;

use Psamatt\ExpenditureBundle\Repository\RepositoryInterface;
use Psamatt\ExpenditureBundle\Repository\Exception\ItemNotFoundException;

use Psamatt\ExpenditureBundle\Entity\MonthExpenditureTemplate;
use Psamatt\ExpenditureBundle\ExpenditureEvents;
use Psamatt\ExpenditureBundle\Event\NoticeMessageEvent;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A service record to handle all templates actions
 */
class ExpenditureTemplateService extends BasePageAction
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
     * Save a template record
     *
     * @param MonthExpenditureTemplate $template
     * @return boolean
     */
    public function saveTemplate(MonthExpenditureTemplate $template)
    {
        $this->repository->save($template, true);
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new NoticeMessageEvent('Default Payment Saved'));
        
        return true;
    }
    
    /**
     * All actions related to deleting a template record
     *
     * @param MonthExpenditureTemplate $template
     * @return boolean
     */
    public function deleteTemplate(MonthExpenditureTemplate $template)
    {
        $this->repository->delete($template, true);
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new NoticeMessageEvent('Default Payment Deleted'));

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
     * Find all the user 
     *
     * @param User $user
     * @return array[]
     */
    public function findAllByUser($user)
    {
        return $this->repository->findBy(array('user' => $user), array('price' => 'DESC'));
    }
}