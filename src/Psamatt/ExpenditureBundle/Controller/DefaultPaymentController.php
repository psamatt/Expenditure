<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Psamatt\ExpenditureBundle\Entity\MonthExpenditureTemplate;

class DefaultPaymentController extends BaseController
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
     * View a default payment
     *
     * @param integer $defaultID The ID of the default to add / edit
     * @return Response
     */
    public function viewAction($defaultID = 0)
    {
       $returnArray = array();

        if ($defaultID > 0) {
            $default = $this->em->getRepository('PsamattExpenditureBundle:MonthExpenditureTemplate')->find($defaultID);
            
            $this->isOwnedByAdmin($default);

            if ($default !== false) {
                $returnArray['defaultObj'] = $default;
            }
        }

        $returnArray['monthlyTemplates'] = $this->getUser()->getExpenditureTemplates();

        return $this->templating->renderResponse('PsamattExpenditureBundle:default:overview.html.twig', $returnArray);
    }

    /**
     * Save a default payment
     *
     * @param integer $defaultID
     * @param Request $request
     * @return RedirectResponse
     */
    public function saveAction($defaultID, Request $request)
    {
        $monthExpenditureTemplate = new MonthExpenditureTemplate;

        if ($defaultID > 0) {
            $monthExpenditureTemplate = $this->em->getRepository('PsamattExpenditureBundle:MonthExpenditureTemplate')->find($defaultID);
            
            $this->isOwnedByAdmin($monthExpenditureTemplate);
        }

        $monthExpenditureTemplate->setTitle($request->get('inputTitle'));
        $monthExpenditureTemplate->setPrice($request->get('inputPrice'));
        $monthExpenditureTemplate->setUser($this->getUser());

        $this->em->persist($monthExpenditureTemplate);
        $this->em->flush();
        
        $this->addNotice('Default Payment Saved');

        return new RedirectResponse($this->router->generate('admin_payments'), 302);
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
        $monthExpenditureTemplate = $this->em->getRepository('PsamattExpenditureBundle:MonthExpenditureTemplate')->find($defaultID);
        
        $this->isOwnedByAdmin($monthExpenditureTemplate);

        $this->em->remove($monthExpenditureTemplate);
        $this->em->flush();
        
        $this->addNotice('Default Payment Deleted');

        return new RedirectResponse($this->router->generate('admin_payments'), 302);
    }
}