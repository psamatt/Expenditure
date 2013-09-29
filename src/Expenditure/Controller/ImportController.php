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
        $monthHeader = new \Expenditure\Entity\MonthHeader;
        $monthHeader->setCalendarDate($this->getCarbon()->createFromDate($year, $month, 1)->toDateString());
        $monthHeader->setMonthIncome($request->get('salary'));
        $monthHeader->setUser($this->getUser());

        $monthlyTemplates = $this->getUser()->getExpenditureTemplates();

        if (count($monthlyTemplates) > 0) {

            foreach ($monthlyTemplates as $monthlyTemplate) {

                $monthExpenditure = new \Expenditure\Entity\MonthExpenditure;
                $monthExpenditure->setTitle($monthlyTemplate->getTitle());
                $monthExpenditure->setPrice($monthlyTemplate->getPrice());
                $monthExpenditure->setAmountPaid(0);

                $monthHeader->addExpenditure($monthExpenditure);
            }
        }
        
        $this->em->persist($monthHeader);
        $this->em->flush();

        return new RedirectResponse($this->urlGenerator->generate('admin_homepage'), 302);
    }
}