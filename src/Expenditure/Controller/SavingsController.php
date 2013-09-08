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
        $returnArray['savings'] = $this->getUser()->savings;;

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
        $saving = new \Expenditure\Model\Saving;
        
        if ('' !== $savingID = $request->get('savingID', '')) {
            $saving = $this->db->first('Expenditure\Model\Saving', array('id' => $savingID));
            
            $this->isOwnedByAdmin($saving);
        }

        $saving->title = $request->get('inputTitle');
        $saving->target_date = $request->get('targetDate');
        $saving->target_amount = $request->get('targetAmount');
        $saving->saved_amount = $request->get('amountSaved');
        
        if ($saving->isGoalReached()) {
            $saving->saved_amount = $saving->target_amount;
        }
        $saving->user_id = $this->getUser()->id;

        $this->db->save($saving);

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
        $saving = $this->db->first('Expenditure\Model\Saving', array('id' => $savingID));
        
        $this->isOwnedByAdmin($saving);
        
        $this->db->delete($saving);
        
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
        $saving = $this->db->first('Expenditure\Model\Saving', array('id' => $savingID));
        
        $this->isOwnedByAdmin($saving);
        
        $saving->addMoney(floatval($request->get('amount', 0)));
        
        $this->db->save($saving);
        
        return 1;
    }
}