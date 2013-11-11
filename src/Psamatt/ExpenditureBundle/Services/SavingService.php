<?php

namespace Psamatt\ExpenditureBundle\Services;

use Psamatt\ExpenditureBundle\Repository\RepositoryInterface;
use Psamatt\ExpenditureBundle\Repository\Exception\ItemNotFoundException;

use Psamatt\ExpenditureBundle\Entity\Saving;
use Psamatt\ExpenditureBundle\ExpenditureEvents;
use Psamatt\ExpenditureBundle\Event\NoticeMessageEvent;
use Psamatt\Expenditure\Library\SavingMoney;

use Symfony\Component\EventDispatcher\EventDispatcher;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * A service record to handle all Saving actions
 */
class SavingService extends BasePageAction
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
     * Save a saving record
     *
     * @param Saving $saving
     * @return boolean
     */
    public function saveSaving(Saving $saving)
    {
        $this->repository->save($saving, true);
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new NoticeMessageEvent('Saving Saved'));
        
        return true;
    }
    
    /**
     * All actions related to deleting a saving record
     *
     * @param Saving $saving
     * @return boolean
     */
    public function deleteSaving(Saving $saving)
    {
        $this->repository->delete($saving, true);
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new NoticeMessageEvent('Saving Deleted'));

        return true;
    }

    /**
     * Find a saving item by its ID
     *
     * @param integer $id
     * @return object
     */
    public function findById($id)
    {
        $saving = $this->repository->findById($id);
        
        if (!$saving) {
            throw new ItemNotFoundException;
        }
        
        return $saving;
    }
    
    /**
     * Find all the user 
     *
     * @param User $user
     * @return array[]
     */
    public function findAllByUser($user)
    {
        return $this->repository->findBy(array('user' => $user), array('target_date' => 'ASC'));
    }

}