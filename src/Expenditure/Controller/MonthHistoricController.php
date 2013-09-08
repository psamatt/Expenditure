<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MonthHistoricController extends DefaultController
{
    /**
     * View all historic month headers
     *
     * @return Response
     */
    public function indexAction()
    {
        $monthHeaders = $this->getUser()->monthHeaders;

        return $this->twig->render('historic/overview.html.twig', array('monthHeaders' => $monthHeaders));
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

        $monthHeader = $this->db->first('Expenditure\Model\MonthHeader', array('calendar_date' => $this->getCarbon()->createFromDate($year, $month, 1)->toDateString(), 'user_id' => $this->getUser()->id));
        
        if (!$monthHeader) {
            return new RedirectResponse($this->urlGenerator->generate('admin_homepage'), 302);
        }

        $monthlyExpenditure = $this->db->all('Expenditure\Model\MonthExpenditure')->where(array('header_id' => $monthHeader->id));

        list($totalPaid, $totalExpenditure) = $this->findMonthlyTotals($monthlyExpenditure->toArray());

        $returnArray['totalPaid'] = $totalPaid;
        $returnArray['monthlyExpenditure'] = $monthlyExpenditure;
        $returnArray['monthHeader'] = $monthHeader;

        return $this->twig->render('historic/month_overview.html.twig', $returnArray);
    }
}