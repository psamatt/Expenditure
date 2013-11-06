<?php

namespace Psamatt\ExpenditureBundle\Listener;

use Psamatt\ExpenditureBundle\Event\MessageEventInterface;
use Psamatt\ExpenditureBundle\ExpenditureEvents;
use Symfony\Component\HttpFoundation\Session\Session;

use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service
 */
class PageNotifyListener
{
    /**
     * Session
     * 
     */
    private $session;

    /**
     * Constructor
     *
     * @DI\InjectParams({
     *     "session" = @DI\Inject("session"),
     * })
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }
    
    /**
     * Notify a page of a message
     *
     * @DI\Observe(ExpenditureEvents::NOTIFY_PAGE)
     * @DI\Observe(ExpenditureEvents::ERROR_PAGE)
     * @param MessageEventInterface $event The event containing the message
     */
    public function onNotifyPage(MessageEventInterface $event)
    {
        $this->session->getFlashBag()->add($event->getMessageType(), $event->getMessage());
    }

}
