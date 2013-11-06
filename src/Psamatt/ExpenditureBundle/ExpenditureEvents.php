<?php

namespace Psamatt\ExpenditureBundle;

final class ExpenditureEvents
{
    /**
     * The page.notify event is thrown each time you want to notify 
     * the page
     *
     * The event listener receives an
     * Psamatt\ExpenditureBundle\Event\MessageEvent instance.
     *
     * @var string
     */
    const NOTIFY_PAGE = 'page.notify';
    
    /**
     * The page.error event is thrown each time you want to error 
     * on the page
     *
     * The event listener receives an
     * Psamatt\ExpenditureBundle\Event\ErrorMessageEvent instance.
     *
     * @var string
     */
    const ERROR_PAGE = 'page.error';
}