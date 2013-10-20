<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
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
     * Homepage showing an overview of this months expenditure
     *
     */
    public function indexAction()
    {
        $monthHeader = $this->em->getRepository('PsamattExpenditureBundle:MonthHeader')->findOneBy(array('user' => $this->getUser()), array('calendar_date' => 'DESC'));

        $currentFormat = (int)$this->getCarbon()->startOfMonth()->format('Ym');
        $month = $this->getCarbon();

        if ($this->request->get('newMonth') !== null) {
            $monthHeader = null;
            $month = $this->getCarbon()->addMonth();
        } elseif ($monthHeader !== null) {

             $month = $this->getCarbon($monthHeader->getCalendarDate()->format('c'));

            if ($currentFormat > (int)$monthHeader->getCalendarDate()->format('Ym')) {
                $monthHeader = null;
                $month = $this->getCarbon();
            }
        }       

        $returnArray = array('monthYear' => $month->startOfMonth());

        if ($monthHeader !== null) {

            $monthlyExpenditures = $this->em->getRepository('PsamattExpenditureBundle:MonthExpenditure')->findBy(array('header' => $monthHeader), array('price' => 'DESC'));

            list($totalPaid, $totalExpenditure) = $this->findMonthlyTotals($monthlyExpenditures);

            $returnArray['totalPaid'] = $totalPaid;
            $returnArray['totalExpenditure'] = $totalExpenditure;
            $returnArray['monthlyExpenditures'] = $monthlyExpenditures;

            $returnArray['monthHeader'] = $monthHeader;
        } else {
            $returnArray['notFound'] = true;
        }

        return $this->templating->renderResponse('PsamattExpenditureBundle::overview.html.twig', $returnArray);
    }

    /**
     * Find monthly totals for a specific array set of expenditure
     *
     * @param array[MonthExpenditure] An array of expenditure
     * @return array[int, int]
     */
    protected function findMonthlyTotals($expenditure)
    {
        $totalPaid = $totalExpenditure = 0;

        for ($i=0, $j = count($expenditure); $i < $j; $i++) {

            $item = $expenditure[$i];
            $totalPaid += $item->getAmountPaid();
            $totalExpenditure += $item->getPrice();
        }

        return array($totalPaid, $totalExpenditure);
    }
}