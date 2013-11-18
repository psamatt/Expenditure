<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use JMS\DiExtraBundle\Annotation\Inject;

use Psamatt\ExpenditureBundle\Repository\Exception\ItemNotFoundException;
use Psamatt\ExpenditureBundle\Entity\MonthExpenditure;
use Psamatt\ExpenditureBundle\Form\Type\ExpenditurePartialPaidMoneyType;

class MonthExpenditureController extends BaseController
{
    /* DI Injected variables */
    protected $templating;
    protected $security;
    protected $router;
    protected $request;
    protected $formFactory;
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
     * Mark an expenditure as paid
     *
     * @param integer $expenditureID The expenditure to mark as paid
     * @return Response
     */
    public function paidAction($expenditureID)
    {
        $expenditure = $this->monthExpenditureService->findById($expenditureID);
        $monthHeader = $this->monthHeaderService->findById($expenditure->getHeaderId());
        // check to make sure the monthHeader is owned by the user
        $this->monthHeaderService->isOwnedByAdmin($monthHeader, $this->getUser());
        
        $form = $this->formFactory->create(new ExpenditurePartialPaidMoneyType);
        
        if ($this->request->getMethod() == 'POST') {
        
            $form->bind($this->request);

            $expenditure->addPayment($this->request->request->has('full')? $expenditure->getPrice(): $form['amount']->getData());
    
            $this->monthExpenditureService->savePayment($expenditure);
    
            if ($this->request->isXmlHttpRequest()) {
                return new Response(1);
            }
    
            return new RedirectResponse($this->router->generate('admin_homepage'), 302);
        }
        
        return $this->templating->renderResponse('PsamattExpenditureBundle:snippets:expenditurePartialPaid.html.twig', array(
                'form' => $form->createView(),
                'expenditure' => $expenditure,
            ));
    }

    /**
     * Save a new expenditure item
     *
     * @param integer $headerID The header
     * @return RedirectResponse
     */
    public function saveAction($headerID)
    {
        $monthHeader = $this->monthHeaderService->findById($headerID);
        // check to make sure the monthHeader is owned by the user
        $this->monthHeaderService->isOwnedByAdmin($monthHeader, $this->getUser());
        
        $expenditureID = $this->request->get('expenditureID');
        
        $expenditure = new MonthExpenditure;

        if ($expenditureID > 0) {
            $expenditure = $this->monthExpenditureService->findById($expenditureID);
            
            if ($expenditure->getHeaderId() != $headerID) {
                throw new ItemNotFoundException;
            }
        }
        
        $expenditure->update(
                    $this->request->get('title'), 
                    $this->request->get('price'), 
                    $monthHeader
                );
       
        $this->monthExpenditureService->save($expenditure);

        return new RedirectResponse($this->router->generate('admin_homepage'), 302);
    }

    /**
     * Delete an expenditure item
     *
     * @param integer $headerID The header ID
     * @param integer $expenditureID The expenditure ID
     * @return Response
     */
    public function deleteAction($headerID, $expenditureID)
    {
        $expenditure = $this->monthExpenditureService->findById($expenditureID);

        if ($headerID != $expenditure->getHeaderId()) {
            throw new ItemNotFoundException;
        }
        
        $monthHeader = $this->monthHeaderService->findById($headerID);
        // check to make sure the monthHeader is owned by the user
        $this->monthHeaderService->isOwnedByAdmin($monthHeader, $this->getUser());
        
        if (null !== $this->request->get('confirmDelete')) {
            $this->monthExpenditureService->delete($expenditure);
        }
        if ($this->request->isXmlHttpRequest()) {
            return new Response(1);
        }
        
        return new RedirectResponse($this->router->generate('admin_homepage'), 302);
    }
    
}