<?php

namespace Psamatt\Pecunia\Application\UI\Web\WebBundle\Controller\Dashboard;

use Symfony\Component\HttpFoundation\Request;

use Psamatt\Pecunia\Application\UI\Web\SharedBundle\Util\ControllerUtils;
use Psamatt\Pecunia\Query\LatestExpenditureMonthQuery;

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

        $viewModel = $mediator->request(new LatestExpenditureMonthQuery($this->utils->getUser()->getId()));

        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle:Dashboard:overview.html.twig', ['viewModel' => $viewModel]);
    }
}