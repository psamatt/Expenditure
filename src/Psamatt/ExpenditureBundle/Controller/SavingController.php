<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Psamatt\ExpenditureBundle\Entity\Saving;
use Psamatt\Expenditure\Library\SavingMoney;

class SavingController extends BaseController
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
     * Display all savings
     *
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $returnArray['savings'] = $this->getUser()->getSavings();

        return $this->templating->renderResponse('PsamattExpenditureBundle:savings:overview.html.twig', $returnArray);
    }
    
    /**
     * Save a savings record
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction(Request $request)
    {
        $saving = new Saving;
        
        if ('' !== $savingID = $request->get('savingID', '')) {
            $saving = $this->em->getRepository('PsamattExpenditureBundle:Saving')->find($savingID);
            
            $this->isOwnedByAdmin($saving);
        }
        
        $targetDate = $request->get('targetDate', null);

        if (preg_match('/([0-9]{2})-([0-9]{2})-([0-9]{4})/', $targetDate, $m)) {
            $targetDate = $this->getCarbon()->createFromDate($m[3], $m[2], $m[1]);
        } else {
            $targetDate = null;
        }

        $saving->setTitle($request->get('inputTitle'));
        $saving->setTargetDate($targetDate);
        $saving->setTargetAmount($request->get('targetAmount'));
        $saving->setSavedAmount($request->get('amountSaved'));
        $saving->setUser($this->getUser());
        
        if ($saving->isGoalReached()) {
            $saving->setSavedAmount($saving->getTargetAmount());
        }

        $this->em->persist($saving);
        $this->em->flush();
        
        $this->session->getFlashBag()->add('notice', 'Saving Saved');

        return new RedirectResponse($this->router->generate('admin_savings'), 302);
    }
    
    /**
     * Delete a saving record
     *
     * @param integer $savingID The ID of the saving
     * @return RedirectResponse 
     */
    public function deleteAction($savingID)
    {
        $saving = $this->em->getRepository('PsamattExpenditureBundle:Saving')->find($savingID);
        
        $this->isOwnedByAdmin($saving);

        $this->em->remove($saving);
        $this->em->flush();
        
        $this->session->getFlashBag()->add('notice', 'Saving Deleted');
        
        return new RedirectResponse($this->router->generate('admin_savings'), 302);
    }
    
    /**
     * Add money to a savings account
     *
     * @param integer $savingID
     * @param Request $request
     * @return 1
     */
    public function addMoneyAction($savingID, Request $request)
    {
        $saving = $this->em->getRepository('PsamattExpenditureBundle:Saving')->find($savingID);
        
        $this->isOwnedByAdmin($saving);
        
        $saving->addMoney(new SavingMoney($request->get('amount', 0)));
        
        $this->em->persist($saving);
        $this->em->flush();
        
        return 1;
    }
}