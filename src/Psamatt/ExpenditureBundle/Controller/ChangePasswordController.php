<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation\Inject;

use Psamatt\ExpenditureBundle\Form\Type\ChangePasswordType;

class ChangePasswordController extends BaseController
{   
    /**
     * User service
     * @Inject("user.service", required=true)
     */
    private $userService;
    
    /**
     * Dispatcher
     * @Inject("event_dispatcher", required=true)
     */
    private $dispatcher;
    
     /**
     * 
     * @Inject("managePassword.command", required=true)
     */
    private $managePasswordCommand;

    /**
     *
     * @Inject("form.factory", required=true)
     */
    private $formFactory;
    
    /* DI Injected variables */
    protected $templating;
    protected $security;
    protected $router;
    protected $session;
    protected $request;
    /* End of Injected variables */
    
    /**
     * Change a users password
     *
     * @return Response
     */
    public function amendAction()
    {
        $user = $this->getUser();
        $this->managePasswordCommand->setUser($user);
        
        $form = $this->formFactory->create(new ChangePasswordType, $this->managePasswordCommand);
    
        if ($this->request->getMethod() == 'POST') {
        
            $form->bind($this->request);
            
            if ($form->isValid()) {

                $user->updateSecurityDetails($this->managePasswordCommand->getEncryptedPassword($user->getSalt()));

                $this->userService->save($user, 'Password Updated');
                
                return new Response(1);
            }
            
            return $this->templating->renderResponse('PsamattExpenditureBundle:form:errors.html.twig', array(
                    'errors' => $form->getErrors(),
                ), new Response('',500));
        }
        
        $returnArray['passwordForm'] = $form->createView();
        
        return $this->templating->renderResponse('PsamattExpenditureBundle:snippets:amendPassword.html.twig', $returnArray);
    }
}