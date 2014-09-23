<?php

namespace Psamatt\Pecunia\Application\UI\Web\WebBundle\Controller\ExpenditureMonth;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Psamatt\Pecunia\Application\UI\Web\SharedBundle\Util\ControllerUtils;

use Psamatt\Pecunia\Query\AllExpenditureMonthOverviewQuery;
use Psamatt\Pecunia\Query\SpecificExpenditureMonthQuery;

use Money\Money;

use JMS\DiExtraBundle\Annotation as DI;

class MonthHistoricController
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

    public function indexAction()
    {
        $mediator = $this->utils->getMediator();

        $viewModel = $mediator->request(new AllExpenditureMonthOverviewQuery($this->utils->getUser()->getId()));

        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle:Month:all_overview.html.twig', ['viewModel' => $viewModel]);
    }

    public function viewAction($year, $month)
    {
        $mediator = $this->utils->getMediator();

        $viewModel = $mediator->request(new SpecificExpenditureMonthQuery($this->utils->getUser()->getId(), $year, $month));

        return $this->utils->render('PsamattPecuniaApplicationUIWebWebBundle:Month:view_historic.html.twig', ['viewModel' => $viewModel]);
    }
}