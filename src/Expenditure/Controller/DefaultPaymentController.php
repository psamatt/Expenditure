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
            $default = $this->em->getRepository('Expenditure:MonthExpenditureTemplate')->find($defaultID);
            
            $this->isOwnedByAdmin($default);

            if ($default !== false) {
                $returnArray['defaultObj'] = $default;
            }
        }

        $returnArray['monthlyTemplates'] = $this->getUser()->getExpenditureTemplates();

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
            $monthExpenditureTemplate = $this->em->getRepository('Expenditure:MonthExpenditureTemplate')->find($defaultID);
            
            $this->isOwnedByAdmin($monthExpenditureTemplate);

        } else {
            $monthExpenditureTemplate = new \Expenditure\Entity\MonthExpenditureTemplate;
        }

        $monthExpenditureTemplate->setTitle($request->get('inputTitle'));
        $monthExpenditureTemplate->setPrice($request->get('inputPrice'));
        $monthExpenditureTemplate->setUser($this->getUser());

        $this->em->persist($monthExpenditureTemplate);
        $this->em->flush();
        
        $this->session->getFlashBag()->add('notice', 'Default Payment Saved');

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
        $monthExpenditureTemplate = $this->em->getRepository('Expenditure:MonthExpenditureTemplate')->find($defaultID);
        
        $this->isOwnedByAdmin($monthExpenditureTemplate);

        $this->em->remove($monthExpenditureTemplate);
        $this->em->flush();
        
        $this->session->getFlashBag()->add('notice', 'Default Payment Deleted');

        return new RedirectResponse($this->urlGenerator->generate('admin_payments'), 302);
    }
}