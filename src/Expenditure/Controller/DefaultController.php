<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * Homepage showing an overview of this months expenditure
     *
     * @Request $request
     */
    public function indexAction(Request $request)
    {
        $monthHeader = $this->em->getRepository('Expenditure:MonthHeader')->findOneBy(array('user' => $this->getUser()), array('calendar_date' => 'DESC'));

        $currentFormat = (int)$this->getCarbon()->startOfMonth()->format('Ym');
        $month = $this->getCarbon();

        if ($request->get('newMonth') !== null) {
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

            $monthlyExpenditures = $this->em->getRepository('Expenditure:MonthExpenditure')->findBy(array('header' => $monthHeader), array('price' => 'DESC'));

            list($totalPaid, $totalExpenditure) = $this->findMonthlyTotals($monthlyExpenditures);

            $returnArray['totalPaid'] = $totalPaid;
            $returnArray['totalExpenditure'] = $totalExpenditure;
            $returnArray['monthlyExpenditures'] = $monthlyExpenditures;

            $returnArray['monthHeader'] = $monthHeader;
        } else {
            $returnArray['notFound'] = true;
        }

        return $this->twig->render('overview.html.twig', $returnArray);
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