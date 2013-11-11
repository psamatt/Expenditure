<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use JMS\DiExtraBundle\Annotation\Inject;

use Psamatt\ExpenditureBundle\Library\MonthExpenditureTotalPaid;
use Psamatt\ExpenditureBundle\Library\MonthExpenditureTotalPrice;

class DefaultController extends BaseController
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
     * Homepage showing an overview of this months expenditure
     *
     * @return Response
     */
    public function indexAction()
    {
        $tmp = 'PsamattExpenditureBundle::overview.html.twig';
        
        $currentDate = new \DateTime;
        $currentDate->modify('first day of this month');

        // if we've requested a new month
        if ($this->request->get('newMonth') !== null) {

            $currentDate = $currentDate->modify('+1 month');
            
            return $this->templating->renderResponse($tmp, array(
                    'notFound' => true,
                    'currentDate' => $currentDate
                ));
        }
        
        $returnArray = array();
        
        try {
            $monthHeader = $this->monthHeaderService->findLatestByUser($this->getUser());
            
            $currentDate = $monthHeader->getCalendarDate();

            $monthlyExpenditures = $this->monthExpenditureService->findAllByHeader($monthHeader);

            list($returnArray['totalPaid'], $returnArray['totalExpenditure']) = $this->findMonthlyTotals($monthlyExpenditures);

            $returnArray['monthlyExpenditures'] = $monthlyExpenditures;
            $returnArray['monthHeader'] = $monthHeader;

        } catch (\Psamatt\ExpenditureBundle\Repository\Exception\ItemNotFoundException $e) {
            $returnArray['notFound'] = true;
        }
        
        $returnArray['currentDate'] = $currentDate;

        return $this->templating->renderResponse($tmp, $returnArray);
    }

    /**
     * Find monthly totals for a specific array set of expenditure
     *
     * @param array[MonthExpenditure] $items An array of expenditure
     * @return array[float, float]
     */
    protected function findMonthlyTotals(array $items)
    {
        $totalPaidItems = new MonthExpenditureTotalPaid($items);
        $totalPriceItems = new MonthExpenditureTotalPrice($items);

        return array($totalPaidItems->count(), $totalPriceItems->count());
    }
}