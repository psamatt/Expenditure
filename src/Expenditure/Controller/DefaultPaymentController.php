<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultPaymentController extends BaseController
{
    /**
     * View a default payment
     *
     * @param integer $defaultID The ID of the default to add / edit
     * @return Response
     */
    public function viewAction($defaultID = 0)
    {
       $returnArray = array();

        if ($defaultID > 0) {
            $default = $this->db->first('Expenditure\Model\MonthExpenditureTemplate', array('id' => $defaultID));
            
            $this->isOwnedByAdmin($default);

            if ($default !== false) {
                $returnArray['defaultObj'] = $default;
            }
        }

        $returnArray['monthlyTemplates'] = $this->getUser()->monthExpenditureTemplates;

        return $this->twig->render('default/overview.html.twig', $returnArray);
    }

    /**
     * Save a default payment
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction(Request $request)
    {
        if (null !== $defaultID = $request->get('defaultID')) {
            $monthExpenditureTemplate = $this->db->first('Expenditure\Model\MonthExpenditureTemplate', array('id' => $defaultID));
            
            $this->isOwnedByAdmin($monthExpenditureTemplate);

        } else {
            $monthExpenditureTemplate = new \Expenditure\Model\MonthExpenditureTemplate;
        }

        $monthExpenditureTemplate->title = $request->get('inputTitle');
        $monthExpenditureTemplate->price = $request->get('inputPrice');
        $monthExpenditureTemplate->user_id = $this->getUser()->id;

        $this->db->save($monthExpenditureTemplate);

        return new RedirectResponse($this->urlGenerator->generate('admin_payments'), 302);
    }

    /**
     * Delete a default payment
     *
     * @param integer $defaultID The ID of the default payment to delete
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteAction($defaultID, Request $request)
    {
        $monthExpenditureTemplate = $this->db->first('Expenditure\Model\MonthExpenditureTemplate', array('id' => $defaultID));
        
        $this->isOwnedByAdmin($monthExpenditureTemplate);

        $this->db->delete($monthExpenditureTemplate);

        return new RedirectResponse($this->urlGenerator->generate('admin_payments'), 302);
    }
}