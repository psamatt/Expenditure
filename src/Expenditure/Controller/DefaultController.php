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
        $monthHeader = $this->db->all('Expenditure\Model\MonthHeader', array('user_id' => $this->getUser()->id))->order(array('calendar_date' => 'DESC'))->first();

        $currentFormat = (int)$this->getCarbon()->startOfMonth()->format('Ym');
        $month = $this->getCarbon();

        if ($request->get('newMonth') !== null) {
            $monthHeader = false;
            $month = $this->getCarbon()->addMonth();
        } elseif ($monthHeader !== false) {

             $month = $this->getCarbon($monthHeader->calendar_date->format('c'));

            if ($currentFormat > (int)$monthHeader->calendar_date->format('Ym')) {
                $monthHeader = false;
                $month = $this->getCarbon();
            }
        }        

        $returnArray = array('monthYear' => $month->startOfMonth());

        if ($monthHeader !== false) {

            $monthlyExpenditure = $this->db->all('Expenditure\Model\MonthExpenditure')->where(array('header_id' => $monthHeader->id))->order(array('price' => 'DESC'));

            list($totalPaid, $totalExpenditure) = $this->findMonthlyTotals($monthlyExpenditure->toArray());

            $returnArray['totalPaid'] = $totalPaid;
            $returnArray['totalExpenditure'] = $totalExpenditure;
            $returnArray['monthlyExpenditure'] = $monthlyExpenditure;

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
            $totalPaid += $item['amount_paid'];
            $totalExpenditure += $item['price'];
        }

        return array($totalPaid, $totalExpenditure);
    }
}