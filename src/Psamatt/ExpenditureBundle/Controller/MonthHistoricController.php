<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

use JMS\DiExtraBundle\Annotation\Inject;

class MonthHistoricController extends DefaultController
{
    /* DI Injected variables */
    protected $templating;
    protected $security;
    protected $router;
    protected $request;
    /* End of Injected variables */
    
    /**
     * @Inject("monthExpenditure.service", required=true) 
     */
    protected $monthExpenditureService;
    
    /**
     * @Inject("monthHeader.service", required=true) 
     */
    protected $monthHeaderService;

    /**
     * View all historic month headers
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->templating->renderResponse('PsamattExpenditureBundle:historic:overview.html.twig', array(
                'monthHeaders' => $this->monthHeaderService->findAllByUser($this->getUser())
            ));
    }

    /**
     * View a particular historic month
     *
     * @param integer $year The year to view from
     * @param integer $month The month to view
     * @return Response
     */
    public function viewAction($year, $month)
    {
        $returnArray = array();
        
        $monthHeader = $this->monthHeaderService->findByDateAndUser(
                \DateTime::createFromFormat('Y-m-d', $year. '-' . $month . '-01'),
                $this->getUser()
            );

        if ($monthHeader == null) {
            return new RedirectResponse($this->urlGenerator->generate('admin_homepage'), 302);
        }
        
        $monthlyExpenditures = $this->monthExpenditureService->findAllByHeader($monthHeader);

        list($totalPaid, $totalExpenditure) = $this->findMonthlyTotals($monthlyExpenditures);

        return $this->templating->renderResponse('PsamattExpenditureBundle:historic:month_overview.html.twig', array(
                'totalPaid' => $totalPaid,
                'monthlyExpenditures' => $monthlyExpenditures,
                'monthHeader' => $monthHeader,
            ));
    }
}