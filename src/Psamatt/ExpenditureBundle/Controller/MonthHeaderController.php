<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

use JMS\DiExtraBundle\Annotation\Inject;

use Psamatt\ExpenditureBundle\Entity\MonthHeader;

class MonthHeaderController extends BaseController
{
    /* DI Injected variables */
    protected $templating;
    protected $security;
    protected $router;
    protected $request;
    /* End of Injected variables */
    
    /**
     * @Inject("monthHeader.service", required=true) 
     */
    protected $monthHeaderService;
    
    /**
     * @Inject("expenditureTemplate.service", required=true) 
     */
    protected $expenditureTemplateService;
    
    /**
     * @Inject("templateToExpenditure.transformer", required=true)
     */
    protected $templateToExpenditureTransformer;
    
    /**
     * Create a new month defined by the year and month
     *
     * @param integer $year
     * @param integer $month
     * @return Response
     */
    public function createNewAction($year, $month)
    {
        $monthHeader = new MonthHeader;
        $monthHeader->update(
                    \DateTime::createFromFormat('Y-m-d', $year. '-' . $month . '-01'),
                    $this->request->get('salary'),
                    $user = $this->getUser()
                );

        $monthlyTemplates = $this->expenditureTemplateService->findAllByUser($user);

        if (count($monthlyTemplates) > 0) {
            foreach ($monthlyTemplates as $monthlyTemplate) {
                $monthHeader->addExpenditure($this->templateToExpenditureTransformer->transform($monthlyTemplate));
            }
        }
        
        $this->monthHeaderService->save($monthHeader);

        return new RedirectResponse($this->router->generate('admin_homepage'), 302);
    }
}