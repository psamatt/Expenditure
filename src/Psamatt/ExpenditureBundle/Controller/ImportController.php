<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Psamatt\ExpenditureBundle\Entity\MonthHeader;
use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;

class ImportController extends BaseController
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
     * Save an import
     *
     * @param integer $year The year to import from
     * @param integer $month The month to import
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction($year, $month, Request $request)
    {
        $monthHeader = new MonthHeader;
        $monthHeader->setCalendarDate($this->getCarbon()->createFromDate($year, $month, 1));
        $monthHeader->setMonthIncome($request->get('salary'));
        $monthHeader->setUser($this->getUser());

        $monthlyTemplates = $this->getUser()->getExpenditureTemplates();

        if (count($monthlyTemplates) > 0) {

            foreach ($monthlyTemplates as $monthlyTemplate) {

                $monthExpenditure = new MonthExpenditure;
                $monthExpenditure->setTitle($monthlyTemplate->getTitle());
                $monthExpenditure->setPrice($monthlyTemplate->getPrice());
                $monthExpenditure->setAmountPaid(0);

                $monthHeader->addExpenditure($monthExpenditure);
            }
        }
        
        $this->em->persist($monthHeader);
        $this->em->flush();

        return new RedirectResponse($this->router->generate('admin_homepage'), 302);
    }
}