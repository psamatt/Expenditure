<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;

class MonthExpenditureController extends BaseController
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
     * Mark an expenditure as paid
     *
     * @param integer $expenditureID The expenditure to mark as paid
     * @param Request $request
     * @return Response
     */
    public function paidAction($expenditureID, Request $request)
    {
        $expenditure = $this->em->getRepository('PsamattExpenditureBundle:MonthExpenditure')->find($expenditureID);
        
        $this->isOwnedByAdmin($expenditure->getHeader());

        $expenditure->setAmountPaid(floatval($request->get('amount')));

        $this->em->persist($expenditure);
        $this->em->flush();
        
        $this->addNotice('Expenditure set as ' . (!$expenditure->hasBeenPaid()? 'partially ':'') . 'paid');

        return new Response(1);
    }

    /**
     * Save a new expenditure item
     *
     * @param integer $headerID The header
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction($headerID)
    {
        $expenditureID = $this->request->get('expenditureID');
        $title = $this->request->get('title');
        $price = $this->request->get('price');

        if (strlen($title) > 0 && strlen($price) > 0) {
        
            $monthHeader = $this->em->getRepository('PsamattExpenditureBundle:MonthHeader')->find($headerID);
            $this->isOwnedByAdmin($monthHeader);

            if ($expenditureID > 0) {
                $expenditure = $this->em->getRepository('PsamattExpenditureBundle:MonthExpenditure')->find($expenditureID);
            } else {
                $expenditure = new MonthExpenditure;
            }

            $expenditure->setTitle($title);
            $expenditure->setPrice($price);
            $expenditure->setHeader($monthHeader);

            $this->em->persist($expenditure);
            $this->em->flush();
            
            $this->addNotice('Expenditure Saved');
        }

        return new RedirectResponse($this->router->generate('admin_homepage'), 302);
    }

    /**
     * Delete an expenditure item
     *
     * @param integer $expenditureID The expenditure ID
     * @return Response
     */
    public function deleteAction($expenditureID)
    {
        $expenditure = $this->em->getRepository('PsamattExpenditureBundle:MonthExpenditure')->find($expenditureID);
        
        $this->isOwnedByAdmin($expenditure->getHeader());

        $this->em->remove($expenditure);
        $this->em->flush();

        return new Response(1);
    }
}