<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use JMS\DiExtraBundle\Annotation\Inject;

use Psamatt\ExpenditureBundle\Entity\Saving;
use Psamatt\Expenditure\Library\SavingMoney;

class SavingController extends BaseController
{
    /* DI Injected variables */
    protected $templating;
    protected $security;
    protected $router;
    protected $request;
    /* End of Injected variables */
    
    /**
     * @Inject("saving.service", required=true) 
     */
    protected $savingService;
    
    /**
     * Display all savings for a user
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->templating->renderResponse('PsamattExpenditureBundle:savings:overview.html.twig', array(
            'savings' => $this->savingService->findAllByUser($this->getUser())
        ));
    }
    
    /**
     * Save a savings record
     *
     * @param integer $savingID
     * @return RedirectResponse
     */
    public function saveAction($savingID)
    {
        $saving = new Saving;

        if ($savingID > 0) {
            $saving = $this->savingService->findById($savingID);
            $this->savingService->isOwnedByAdmin($saving, $this->getUser());
        }
        
        $targetDate = null;

        if (preg_match('/([0-9]{2})-([0-9]{2})-([0-9]{4})/', $targetDate = $this->request->get('targetDate', null))) {
            $targetDate = \DateTime::createFromFormat('d-m-Y', $targetDate);
        }
        
        $saving->update(
                $this->request->get('inputTitle'),
                $targetDate,
                $this->request->get('targetAmount'),
                $this->request->get('amountSaved'),
                $this->getUser()
            );
        
        // possibly validate??
        $this->savingService->saveSaving($saving);

        return new RedirectResponse($this->router->generate('admin_savings'), 302);
    }
    
    /**
     * Delete a saving record
     *
     * @param integer $savingID The ID of the saving
     * @return Response 
     */
    public function deleteAction($savingID)
    {
        $saving = $this->savingService->findById($savingID);
        
        $this->savingService->isOwnedByAdmin($saving, $this->getUser());
        $this->savingService->deleteSaving($saving);
        
        if ($this->request->isXmlHttpRequest()) {
            return new Response(1);
        }
        
        return new RedirectResponse($this->router->generate('admin_savings'), 302);
    }
    
    /**
     * Add money to a savings account
     *
     * @param integer $savingID
     * @return Response
     */
    public function addMoneyAction($savingID)
    {
        $saving = $this->savingService->findById($savingID);
        
        $this->savingService->isOwnedByAdmin($saving, $this->getUser());
        $saving->addMoney(new SavingMoney($this->request->get('amount', 0)));
        $this->savingService->saveSaving($saving);
        
        if ($this->request->isXmlHttpRequest()) {
            return new Response(1);
        }
        
        return new RedirectResponse($this->router->generate('admin_savings'), 302);
    }
}