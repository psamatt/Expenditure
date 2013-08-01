<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MonthExpenditureController extends BaseController
{
    /**
     * Mark an expenditure as paid
     *
     * @param integer $expenditureID The expenditure to mark as paid
     * @param Request $request
     * @return 1
     */
    public function paidAction($expenditureID, Request $request)
    {   
        $expenditure = $this->db->first('Expenditure\Model\MonthExpenditure', array('id' => $expenditureID));
        
        $amount = $request->get('amount');
        
        $expenditure->amount_paid = ($amount == 'all'? $expenditure->price: floatval($amount));
        
        $this->db->save($expenditure);
        
        return 1;
    }
    
    /**
     * Save a new expenditure item
     *
     * @param integer $headerID The header 
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction($headerID, Request $request)
    {
        $expenditureID = $request->get('expenditureID');
        $title = $request->get('title');
        $price = $request->get('price');
    
        if (strlen($title) > 0 && strlen($price) > 0) {
        
            if ($expenditureID > 0) {
                $expenditure = $this->db->first('Expenditure\Model\MonthExpenditure', array('id' => $expenditureID));
            } else {
                $expenditure = new \Expenditure\Model\MonthExpenditure;
            }

            $expenditure->title = $title;
            $expenditure->price = $price;
            $expenditure->header_id = $headerID;
    
            $this->db->save($expenditure);
        }
    
        return new RedirectResponse('/', 302);
    }
    
    /**
     * Delete an expenditure item
     *
     * @param integer $expenditureID The expenditure ID
     * @return 1
     */
    public function deleteAction($expenditureID)
    {
        $expenditure = $this->db->first('Expenditure\Model\MonthExpenditure', array('id' => $expenditureID));
        
        $this->db->delete($expenditure);
    
        return 1;
    }
}