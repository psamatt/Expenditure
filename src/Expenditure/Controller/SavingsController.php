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
        $savings = $this->db->all('Expenditure\Model\Saving')->order(array('target_date' => 'DESC'));
        
        $returnArray['savings'] = $savings;

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
        }

        $saving->title = $request->get('inputTitle');
        $saving->target_date = $request->get('targetDate');
        $saving->target_amount = $request->get('targetAmount');
        $saving->saved_amount = $request->get('amountSaved');

        $this->db->save($saving);

        return new RedirectResponse('/savings', 302);
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
        
        $this->db->delete($saving);
        
         return new RedirectResponse('/savings', 302);
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
        
        $saving->addMoney(floatval($request->get('amount', 0)));
        
        $this->db->save($saving);
        
        return 1;
    }
}