<?php

namespace Psamatt\Pecunia\Application\UI\Web\WebBundle\Controller\DefaultPayment;

use Symfony\Component\HttpFoundation\Request;

use Psamatt\Pecunia\Application\UI\Web\SharedBundle\Util\ControllerUtils;
use Psamatt\Pecunia\Query\AllDefaultPaymentQuery;

use Psamatt\Pecunia\Query\Repository\IDefaultRecurringMonthExpenditureRepository;
use Psamatt\Pecunia\Query\Repository\IDefaultOneOffMonthExpenditureRepository;

use Psamatt\Pecunia\Domain\Expenditure\ValueObject\RecurringExpenditureId;
use Psamatt\Pecunia\Domain\Expenditure\ValueObject\OneOffPaymentDueDate;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\NewDefaultRecurringMonthExpenditureCommand;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\NewDefaultOneOffMonthExpenditureCommand;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\RemoveDefaultRecurringMonthExpenditureCommand;
use Psamatt\Pecunia\Domain\Expenditure\PublicAPI\Command\RemoveDefaultOneOffMonthExpenditureCommand;

use Money\Money;

use JMS\DiExtraBundle\Annotation as DI;

class DefaultController
{
    private $utils;
    private $defaultRecurringMonthExpenditureRepository;
    private $defaultOneOffMonthExpenditureRepository;

    /**
     * @DI\InjectParams({
     *     "utils" = @DI\Inject("Pecunia.ControllerUtils"),
     *     "defaultRecurringMonthExpenditureRepository" = @DI\Inject("Pecunia.Query.DefaultRecurringMonthExpenditure.repository"),
     *     "defaultOneOffMonthExpenditureRepository" = @DI\Inject("Pecunia.Query.DefaultOneOffMonthExpenditure.repository")
     * })
     */
    public function __construct(
            ControllerUtils $utils,
            IDefaultRecurringMonthExpenditureRepository $defaultRecurringMonthExpenditureRepository,
            IDefaultOneOffMonthExpenditureRepository $defaultOneOffMonthExpenditureRepository)
    {
        $this->utils = $utils;
        $this->defaultRecurringMonthExpenditureRepository = $defaultRecurringMonthExpenditureRepository;
        $this->defaultOneOffMonthExpenditureRepository = $defaultOneOffMonthExpenditureRepository;

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

        $allDefaultRecurringExpenditures = $this->defaultRecurringMonthExpenditureRepository->findAll($this->utils->getUser()->getId());
        $allDefaultOneOffExpenditures = $this->defaultOneOffMonthExpenditureRepository->findAll($this->utils->getUser()->getId());

        $viewModel = $mediator->request(new AllDefaultPaymentQuery($this->utils->getUser()->getId()));



        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle:Default:overview.html.twig', [
                    'allDefaultRecurringExpenditures' => $allDefaultRecurringExpenditures,
                    'allDefaultOneOffExpenditures' => $allDefaultOneOffExpenditures,
                ]);
    }

    /**
     * register a new default recurring payment
     *
     * @param Request $request
     * @return Response
     */
    public function saveRecurringAction(Request $request)
    {
        $mediator = $this->utils->getMediator();

        $mediator->send(new NewDefaultRecurringMonthExpenditureCommand(
                        $this->utils->getAccountHolderId(),
                        $request->get('description'),
                        Money::{$request->get('currency')}(floatval($request->get('price')))
                    ));

        $this->utils->getUnitOfWork()->flush();
        $this->utils->addConfirmationMessage('Default reccuring monthly payment saved');

        return $this->utils->redirectRoute('default_payments');
    }

    /**
     * register a new default recurring payment
     *
     * @param Request $request
     * @return Response
     */
    public function saveOneOffAction(Request $request)
    {
        try {
            $mediator = $this->utils->getMediator();

            $mediator->send(new NewDefaultOneOffMonthExpenditureCommand(
                            $this->utils->getAccountHolderId(),
                            $request->get('description'),
                            Money::{$request->get('currency')}(floatval($request->get('price'))),
                            new OneOffPaymentDueDate($request->get('year') . '-' . $request->get('month') . '-01')
                        ));

            $this->utils->getUnitOfWork()->flush();
            $this->utils->addConfirmationMessage('Default one off monthly payment saved');
        } catch (\Exception $e) {
            $this->utils->addErrorMessage($e->getMessage());
        }

        return $this->utils->redirectRoute('default_payments', ['tab' => 'oneOff']);
    }

    /**
     * Delete a default recurring payment
     *
     * @param string $defaultId
     * @param Request $request
     * @return Response
     */
    public function deleteRecurringAction($defaultId, Request $request)
    {
        $mediator = $this->utils->getMediator();

        $mediator->send(new RemoveDefaultRecurringMonthExpenditureCommand(
                        $this->utils->getAccountHolderId(),
                        RecurringExpenditureId::bind($defaultId)
                    ));

        $this->utils->getUnitOfWork()->flush();
        $this->utils->addConfirmationMessage('Default reccuring monthly payment removed');

        return $this->utils->redirectRoute('default_payments');
    }

    /**
     * Delete a default recurring payment
     *
     * @param string $defaultId
     * @param Request $request
     * @return Response
     */
    public function deleteOneOffAction($defaultId, Request $request)
    {
        $mediator = $this->utils->getMediator();

        $mediator->send(new RemoveDefaultOneOffMonthExpenditureCommand(
                        $this->utils->getAccountHolderId(),
                        RecurringExpenditureId::bind($defaultId)
                    ));

        $this->utils->getUnitOfWork()->flush();
        $this->utils->addConfirmationMessage('Default oneoff monthly payment removed');

        return $this->utils->redirectRoute('default_payments', ['tab' => 'oneOff']);
    }
}