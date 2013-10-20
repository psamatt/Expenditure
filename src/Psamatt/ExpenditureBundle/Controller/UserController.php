<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\SecurityContext;

use JMS\DiExtraBundle\Annotation\Inject;

use Psamatt\ExpenditureBundle\Entity\User;

class UserController extends BaseController
{
    /**
     * Encoder Factory 
     *
     * @var EncoderFactory
     * @access private
     * @Inject("security.encoder_factory", required=true)
     */
    private $encoderFactory;
    
    /**
     * The validator service
     *
     * @var Validator
     * @access private
     * @Inject("validator", required=true) 
     */
    private $validator;
    
    /* DI Injected variables */
    protected $em;
    protected $templating;
    protected $security;
    protected $router;
    protected $session;
    protected $request;
    /* End of Injected variables */
    
    /**
     * Login to the application
     *
     * @return Response
     */
    public function loginAction()
    {
        $user = $this->getUser();

        if (is_object($user) && $user instanceof User) {
            return new RedirectResponse($this->router->generate('admin_homepage'), 302);
        }
        
        // get the login error if there is one
        if ($this->request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->session->get(SecurityContext::AUTHENTICATION_ERROR);
            $this->session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }    
        return $this->templating->renderResponse('PsamattExpenditureBundle::login.html.twig', array(
            'error'         => $error,
            'last_username' => $this->session->get('_security.last_username'),
        ));
    }
  
    /**
     * Add a user account
     *
     * @return RedirectResponse
     */
    public function newAction()
    {
        $user = $this->getUser();
        
        // if we are already a user - then redirect back to home
        if (is_object($user) && $user instanceof User) {
            return new RedirectResponse($this->router->generate('admin_homepage'), 302);
        }
        
        $returnArray = array();

        if ($this->request->getMethod() == 'POST') {
        
            $hasErrors = false;
            $returnArray['errors'] = array();
            $errors = &$returnArray['errors'];
            
            if ($this->request->get('password1') != $this->request->get('password2')) {
                $errors['password'] = 'Password must match confirmation password';
                $hasErrors = true;
            }
            
            if (!$hasErrors) {

                $user = $this->bindUser(true);
                
                if (!$user->isPasswordValid($this->request->get('password1'))) {
                    $errors['password'] = 'Password must be 6 characters or more long, contain at least one number and an upper case and lower case letter';
                    $hasErrors = true;
                }
                
                if (!$hasErrors) {

                    $returnArray['errors'] = $this->validator->validate($user);
                    
                    if (count($returnArray['errors']) == 0) {
                        $this->em->persist($user);
                        $this->em->flush();
                        
                        $this->addNotice('Account created');

                        return new RedirectResponse($this->router->generate('login'), 302);
                    }                    
                }
            } 
        }
        
        return $this->templating->renderResponse('PsamattExpenditureBundle:profile:new.html.twig', $returnArray);
    }
    
    /**
     * Edit a users profile
     *
     * @return Response 
     */
    public function editAction()
    {
        $returnArray = array();
    
        if ($this->request->getMethod() == 'POST') {

            $user = $this->bindUser(false);
            
            $returnArray['errors'] = $this->validator->validate($user);
            
            if (count($returnArray['errors']) == 0) {
                $this->em->persist($user);
                $this->em->flush();
                
                $this->addNotice('Account updated');
            }
        }
    
        return $this->templating->renderResponse('PsamattExpenditureBundle:profile:edit.html.twig', $returnArray);
    }
    
    /**
     * Change a users password
     *
     * @return Response
     */
    public function changePasswordAction()
    {
        if ($this->request->getMethod() == 'POST') {
            $errors = array();
            $hasErrors = false;
            $user = $this->getUser();
        
            if ($this->request->get('password1') != $this->request->get('password2')) {
                $errors['password'] = 'Password must match confirmation password';
                $hasErrors = true;
            }
            
            if (!$user->isPasswordValid($this->request->get('password1'))) {
                $errors['password'] = 'Password must be 6 characters or more long, contain at least one number and an upper case and lower case letter';
                $hasErrors = true;
            }
            
            if (!$hasErrors) {
            
                $encoder = $this->encoderFactory->getEncoder($user);
                $user->setPassword($encoder->encodePassword($this->request->get('password1'), $user->getSalt()));
                
                $this->em->persist($user);
                $this->em->flush();
                
                $this->addNotice('Password Updated');
            } else {
                $this->session->getFlashBag()->add('passwordErrors', $errors);
            }
        }
        
        return new RedirectResponse($this->router->generate('profile_edit'), 302);
    }
    
    /**
     * Bind a user from the request
     *
     * @param boolean $new Whether or not this is a new user . Default false
     */
    private function bindUser($new = false)
    {
        $user = $new? new User: $this->getUser();
        
        $user->setFullname($this->request->get('fullname'));
        $user->setEmailAddress($this->request->get('emailAddress'));
        $user->setPaidDay($this->request->get('paidDay'));
        
        if ($new) {
            $user->setSalt(hash("sha256",time()));
            $user->setStatus(1);
            
            $encoder = $this->encoderFactory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($this->request->get('password1'), $user->getSalt()));
        }
        
        return $user;
    } 
}