<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use JMS\DiExtraBundle\Annotation\Inject;

use Psamatt\ExpenditureBundle\Entity\MonthHeader;

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
     * Transformer Factory 
     *
     * @access private
     * @Inject("templateToExpenditure.transformer", required=true)
     */
    private $templateToExpenditureTransformer;
    
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
        $monthHeader->setCalendarDate($date = $this->getCarbon()->createFromDate($year, $month, 1));
        $monthHeader->setMonthIncome($request->get('salary'));
        $monthHeader->setUser($this->getUser());

        $monthlyTemplates = $this->getUser()->getExpenditureTemplates();

        if (count($monthlyTemplates) > 0) {

            foreach ($monthlyTemplates as $monthlyTemplate) {
                $monthHeader->addExpenditure($this->templateToExpenditureTransformer->transform($monthlyTemplate));
            }
        }
        
        $this->em->persist($monthHeader);
        $this->em->flush();
        
        $this->addNotice($date->format('F Y') . ' Created');

        return new RedirectResponse($this->router->generate('admin_homepage'), 302);
    }
}