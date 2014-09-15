<?php

namespace Psamatt\Pecunia\Application\UI\Web\WebBundle\Controller\ExpenditureMonth;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Psamatt\Pecunia\Application\UI\Web\SharedBundle\Util\ControllerUtils;

use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\CreateMonthCommand;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\ExpenditureMonthItemNewCommand;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\ExpenditureMonthItemDeleteCommand;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\ExpenditureMonthItemPaidCommand;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\ExpenditureMonthItemPartialPaymentCommand;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\CalendarPeriod;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\ExpenditureItemId;

use Money\Money;

use JMS\DiExtraBundle\Annotation as DI;

class MonthController
{
    private $utils;
    /**
     * @DI\InjectParams({
     *     "utils" = @DI\Inject("Pecunia.ControllerUtils")
     * })
     */
    public function __construct(ControllerUtils $utils)
    {
        $this->utils = $utils;
    }

    /**
     * Create a new month
     *
     * @param Request
     * @return Response
     */
    public function createAction($year, $month, Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $mediator = $this->utils->getMediator();
            $mediator->send(new CreateMonthCommand(
                    $this->utils->getAccountHolderId(),
                    CalendarPeriod::forMonth((int)$month, (int)$year),
                    Money::{$request->get('currency')}(floatval($request->get('amount')))
                ));

            $this->utils->getUnitOfWork()->flush();

            return $this->utils->redirectRoute('accountHolder_homepage');
        }

        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle:Month:create.html.twig');
    }

    /**
     * New item
     *
     * @param   string    $year
     * @param   string    $month
     * @param   Request   $request
     * @return  Response
     */
    public function itemNewAction($year, $month, Request $request)
    {
        $currency = $request->get('currency');

        $this->utils->getMediator()->send(new ExpenditureMonthItemNewCommand(
                $this->utils->getAccountHolderId(),
                CalendarPeriod::forMonth((int)$month, (int)$year),
                $request->get('description'),
                Money::{$currency}(floatval($request->get('price')))
            ));

        $this->utils->getUnitOfWork()->flush();

        return $this->utils->redirectRoute('accountHolder_homepage');
    }

    /**
     * Delete item
     *
     * @param   string    $year
     * @param   string    $month
     * @param   integer   $itemId
     * @param   Request   $request
     * @return  Response
     */
    public function itemDeleteAction($year, $month, $itemId, Request $request)
    {
        $this->utils->getMediator()->send(new ExpenditureMonthItemDeleteCommand(
                $this->utils->getAccountHolderId(),
                CalendarPeriod::forMonth((int)$month, (int)$year),
                new ExpenditureItemId($itemId)
            ));

        $this->utils->getUnitOfWork()->flush();

        if ($request->isXmlHttpRequest()) {
            return new Response(1);
        }

        return $this->utils->redirectRoute('accountHolder_homepage');
    }

    /**
     * Paid item
     *
     * @param   string    $year
     * @param   string    $month
     * @param   integer   $itemId
     * @param   Request   $request
     * @return  Response
     */
    public function itemPaidAction($year, $month, $itemId, Request $request)
    {
        $this->utils->getMediator()->send(new ExpenditureMonthItemPaidCommand(
                $this->utils->getAccountHolderId(),
                CalendarPeriod::forMonth((int)$month, (int)$year),
                new ExpenditureItemId($itemId)
            ));

        $this->utils->getUnitOfWork()->flush();

        if ($request->isXmlHttpRequest()) {
            return new Response(1);
        }

        return $this->utils->redirectRoute('accountHolder_homepage');
    }

    /**
     * Partial Payment item
     *
     * @param   string    $year
     * @param   string    $month
     * @param   integer   $itemId
     * @param   Request   $request
     * @return  Response
     */
    public function itemPartialPaymentAction($year, $month, $itemId, Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $currency = $request->get('currency');

            $this->utils->getMediator()->send(new ExpenditureMonthItemPartialPaymentCommand(
                    $this->utils->getAccountHolderId(),
                    CalendarPeriod::forMonth((int)$month, (int)$year),
                    new ExpenditureItemId($itemId),
                    Money::{$currency}(floatval($request->get('amount')))
                    ));

            $this->utils->getUnitOfWork()->flush();

            if ($request->isXmlHttpRequest()) {
                return new Response(1);
            }

            return $this->utils->redirectRoute('accountHolder_homepage');
        }

        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle:Month:deposit_money.html.twig');
    }
}