<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SavingsController extends BaseController
{
    /**
     * Display all savings
     *
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        $returnArray['savings'] = $this->getUser()->getSavings();

        return $this->twig->render('savings/overview.html.twig', $returnArray);
    }
    
    /**
     * Save a savings record
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction(Request $request)
    {
        $saving = new \Expenditure\Entity\Saving;
        
        if ('' !== $savingID = $request->get('savingID', '')) {
            $saving = $this->em->getRepository('Expenditure:Saving')->find($savingID);
            
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

        return new RedirectResponse($this->urlGenerator->generate('admin_savings'), 302);
    }
    
    /**
     * Delete a saving record
     *
     * @param integer $savingID The ID of the saving
     * @return RedirectResponse 
     */
    public function deleteAction($savingID)
    {
        $saving = $this->em->getRepository('Expenditure:Saving')->find($savingID);
        
        $this->isOwnedByAdmin($saving);
        
        $this->em->remove($saving);
        $this->em->flush();
        
        return new RedirectResponse($this->urlGenerator->generate('admin_savings'), 302);
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
        $saving = $this->em->getRepository('Expenditure:Saving')->find($savingID);
        
        $this->isOwnedByAdmin($saving);
        
        $saving->addMoney(floatval($request->get('amount', 0)));
        
        $this->em->persist($saving);
        $this->em->flush();
        
        return 1;
    }
}