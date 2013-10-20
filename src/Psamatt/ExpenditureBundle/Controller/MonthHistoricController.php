<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MonthHistoricController extends DefaultController
{
    /* DI Injected variables */
    protected $em;
    protected $templating;
    protected $security;
    protected $router;
    protected $session;
    protected $request;
    /* End of Injected variables */

    /**
     * View all historic month headers
     *
     */
    public function indexAction()
    {
        $monthHeaders = $this->getUser()->getMonthHeaders();

        return $this->templating->renderResponse('PsamattExpenditureBundle:historic:overview.html.twig', array('monthHeaders' => $monthHeaders));
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
        
        $monthDate = $this->getCarbon()->createFromDate($year, $month, 1)->setTime(0,0,0);

        $monthHeader = $this->em->getRepository('PsamattExpenditureBundle:MonthHeader')->findOneBy(array('calendar_date' => $monthDate, 'user' => $this->getUser()));
        
        if (!$monthHeader) {
            return new RedirectResponse($this->urlGenerator->generate('admin_homepage'), 302);
        }

        $monthlyExpenditures = $this->em->getRepository('PsamattExpenditureBundle:MonthExpenditure')->findBy(array('header' => $monthHeader));

        list($totalPaid, $totalExpenditure) = $this->findMonthlyTotals($monthlyExpenditures);

        $returnArray['totalPaid'] = $totalPaid;
        $returnArray['monthlyExpenditures'] = $monthlyExpenditures;
        $returnArray['monthHeader'] = $monthHeader;

        return $this->templating->renderResponse('PsamattExpenditureBundle:historic:month_overview.html.twig', $returnArray);
    }
}