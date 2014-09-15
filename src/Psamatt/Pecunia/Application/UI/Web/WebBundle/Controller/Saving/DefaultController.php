<?php

namespace Psamatt\Pecunia\Application\UI\Web\WebBundle\Controller\Saving;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI;
use Money\Money;

use Psamatt\Pecunia\Application\UI\Web\SharedBundle\Util\ControllerUtils;

use Psamatt\Pecunia\Domain\Saving\PublicAPI\Command\DepositSavingMoneyCommand;
use Psamatt\Pecunia\Domain\Saving\PublicAPI\Command\CreateSavingTargetCommand;
use Psamatt\Pecunia\Domain\Saving\PublicAPI\Command\DeleteSavingCommand;

use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingTarget;
use Psamatt\Pecunia\Domain\Saving\ValueObject\SavingId;

use Psamatt\Pecunia\Query\AllSavingsQuery;

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
     * Saving
     *
     * @param Request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $mediator = $this->utils->getMediator();

        $savings = $mediator->request(new AllSavingsQuery($this->utils->getUser()->getId()));

        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle:Saving:overview.html.twig', ['savings' => $savings]);
    }

    /**
     * Save a new Saving
     *
     * @param Request $request
     * @return Response
     */
    public function saveAction(Request $request)
    {
        $mediator = $this->utils->getMediator();

        $mediator->send(new CreateSavingTargetCommand($request->get('description'),
                new SavingTarget(
                    \DateTime::createFromFormat('d-m-Y', $request->get('targetDate')),
                    Money::{$request->get('currency')}(floatval($request->get('targetAmount')))
                ),
                $this->utils->getAccountHolderId()));

        $this->utils->getUnitOfWork()->flush();
        $this->utils->addConfirmationMessage('Saving added');
        $this->utils->redirectRoute('accountHolder_savings');

        return $this->utils->redirectRoute('accountHolder_savings');
    }

    /**
     * Deposit money into a savings account
     *
     * @param string $savingId
     * @param Request $request
     * @return Response
     */
    public function depositMoneyAction($savingId, Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $mediator = $this->utils->getMediator();

            $mediator->send(new DepositSavingMoneyCommand(
                    SavingId::bind($savingId),
                    Money::{$request->get('currency')}(floatval($request->get('amount')))
                )
            );

            $this->utils->getUnitOfWork()->flush();

            return $this->utils->redirectRoute('accountHolder_savings');
        }

        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle:Saving:addMoney.html.twig');
    }

    /**
     * Delete a saving
     *
     * @param string $savingId
     * @return Response
     */
    public function deleteAction($savingId)
    {
        $mediator = $this->utils->getMediator();

        $mediator->send(new DeleteSavingCommand(SavingId::bind($savingId)));

        $this->utils->getUnitOfWork()->flush();
        $this->utils->addConfirmationMessage('Saving deleted');
        return $this->utils->redirectRoute('accountHolder_savings');
    }
}