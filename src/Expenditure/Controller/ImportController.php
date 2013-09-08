<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ImportController extends BaseController
{
    /**
     * Save an import
     *
     * @param integer $year The year to import from
     * @param integer $month The month to import
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction($year, $month, Request $request)
    {
        $monthHeader = new \Expenditure\Model\MonthHeader;
        $monthHeader->calendar_date = $this->getCarbon()->createFromDate($year, $month, 1)->toDateString();
        $monthHeader->month_income = $request->get('salary');
        $monthHeader->user_id = $this->getUser()->id;

        $this->db->save($monthHeader);

        $monthlyTemplates = $this->getUser()->monthExpenditureTemplates;

        if (count($monthlyTemplates) > 0) {

            foreach ($monthlyTemplates as $monthlyTemplate) {

                $monthExpenditure = new \Expenditure\Model\MonthExpenditure;
                $monthExpenditure->title = $monthlyTemplate->title;
                $monthExpenditure->price = $monthlyTemplate->price;
                $monthExpenditure->amount_paid = 0;
                $monthExpenditure->header_id = $monthHeader->id;

                $this->db->save($monthExpenditure);
            }
        }

        return new RedirectResponse($this->urlGenerator->generate('admin_homepage'), 302);
    }
}