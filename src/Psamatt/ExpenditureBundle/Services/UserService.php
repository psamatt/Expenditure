<?php

namespace Psamatt\ExpenditureBundle\Services;

use Psamatt\ExpenditureBundle\Repository\RepositoryInterface;
use Psamatt\ExpenditureBundle\Repository\Exception\ItemNotFoundException;

use Psamatt\ExpenditureBundle\Entity\User;
use Psamatt\ExpenditureBundle\ExpenditureEvents;
use Psamatt\ExpenditureBundle\Event\NoticeMessageEvent;

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A service record to handle all User actions
 */
class UserService extends BasePageAction
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
     * Save a user record
     *
     * @param User $user
     * @param string $msg The notify message. If null then the system created one used
     * @return boolean
     */
    public function save(User $user, $msg = null)
    {
        if ($msg == null) {
            $msg = 'Account ' . ($user->getId() == null? 'Created':'Updated');
        }
    
        $this->repository->save($user, true);
        
        $this->dispatcher->dispatch(ExpenditureEvents::NOTIFY_PAGE, new NoticeMessageEvent($msg));
        
        return true;
    }
}