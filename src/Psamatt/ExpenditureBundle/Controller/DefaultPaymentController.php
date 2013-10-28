<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use JMS\DiExtraBundle\Annotation\Inject;

use Psamatt\ExpenditureBundle\Entity\MonthExpenditureTemplate;

class DefaultPaymentController extends BaseController
{
    /* DI Injected variables */
    protected $templating;
    protected $security;
    protected $router;
    protected $request;
    /* End of Injected variables */
    
    /**
     * @Inject("expenditureTemplate.service", required=true) 
     */
    protected $expenditureTemplateService;
    
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
            $template = $this->expenditureTemplateService->findById($defaultID);
            $this->expenditureTemplateService->isOwnedByAdmin($template, $this->getUser());

            if ($template !== null) {
                $returnArray['defaultObj'] = $template;
            }
        }

        $returnArray['monthlyTemplates'] = $this->expenditureTemplateService->findAllByUser($this->getUser());

        return $this->templating->renderResponse('PsamattExpenditureBundle:default:overview.html.twig', $returnArray);
    }

    /**
     * Save a default payment
     *
     * @param integer $defaultID
     * @return RedirectResponse
     */
    public function saveAction($defaultID)
    {
        $template = new MonthExpenditureTemplate;

        if ($defaultID > 0) {
            $template = $this->expenditureTemplateService->findById($defaultID);
            $this->expenditureTemplateService->isOwnedByAdmin($template, $this->getUser());
        }
        
        $template->update(
                    $this->request->get('inputTitle'), 
                    $this->request->get('inputPrice'),
                    $this->getUser()
                );
        
        $this->expenditureTemplateService->saveTemplate($template);

        return new RedirectResponse($this->router->generate('admin_payments'), 302);
    }

    /**
     * Delete a default payment
     *
     * @param integer $defaultID The ID of the default payment to delete
     * @return RedirectResponse
     */
    public function deleteAction($defaultID)
    {
        $template = $this->expenditureTemplateService->findById($defaultID);
        
        $this->expenditureTemplateService->isOwnedByAdmin($template, $this->getUser());
        $this->expenditureTemplateService->deleteTemplate($template);
        
        if ($this->request->isXmlHttpRequest()) {
            return new Response(1);
        }
        
        return new RedirectResponse($this->router->generate('admin_payments'), 302);
    }
}