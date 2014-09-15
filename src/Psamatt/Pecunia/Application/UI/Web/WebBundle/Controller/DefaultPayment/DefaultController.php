<?php

namespace Psamatt\Pecunia\Application\UI\Web\WebBundle\Controller\DefaultPayment;

use Symfony\Component\HttpFoundation\Request;

use Psamatt\Pecunia\Application\UI\Web\SharedBundle\Util\ControllerUtils;
use Psamatt\Pecunia\Query\AllDefaultPaymentQuery;

use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\NewDefaultRecurringMonthExpenditureCommand;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\RemoveDefaultRecurringMonthExpenditureCommand;

use Money\Money;

use JMS\DiExtraBundle\Annotation as DI;

class DefaultController
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
     * Homepage showing an overview of the current months expenditure
     *
     * @param Request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $mediator = $this->utils->getMediator();

        $viewModel = $mediator->request(new AllDefaultPaymentQuery($this->utils->getUser()->getId()));

        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle:Default:overview.html.twig', ['viewModel' => $viewModel]);
    }

    /**
     * register a new default recurring payment
     *
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request)
    {
        $mediator = $this->utils->getMediator();

        $mediator->send(new NewDefaultRecurringMonthExpenditureCommand(
                        $this->utils->getAccountHolderId(),
                        $request->get('description'),
                        Money::{$request->get('currency')}(floatval($request->get('price')))
                    ));

        $this->utils->getUnitOfWork()->flush();
        $this->utils->addConfirmationMessage('Default reoccuring monthly payment saved');

        return $this->utils->redirectRoute('default_payments');
    }

    /**
     * Delete a default recurring payment
     *
     * @param string $defaultId
     * @param Request $request
     * @return Response
     */
    public function deleteAction($defaultId, Request $request)
    {
        $mediator = $this->utils->getMediator();

        $mediator->send(new RemoveDefaultRecurringMonthExpenditureCommand(
                        $this->utils->getAccountHolderId(),
                        RecurringExpenditureId::bind($defaultId)
                    ));

        $this->utils->getUnitOfWork()->flush();
        $this->utils->addConfirmationMessage('Default reoccuring monthly payment removed');

        return $this->utils->redirectRoute('default_payments');
    }
}