<?php

namespace Psamatt\ExpenditureBundle;

final class ExpenditureEvents
{
    /**
     * The page.notify event is thrown each time you want to notify 
     * the page
     *
     * The event listener receives an
     * Psamatt\ExpenditureBundle\Event\NotifyPageEvent instance.
     *
     * @var string
     */
    const NOTIFY_PAGE = 'page.notify';
}