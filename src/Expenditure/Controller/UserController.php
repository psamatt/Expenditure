<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Validator\Validator;

use Expenditure\Entity\User;

class UserController extends BaseController
{
    /**
     * Encoder Factory 
     *
     * @var EncoderFactory
     * @access private
     */
    private $encoderFactory;
    
    /**
     * The login error if user doesnt specify the correct credentials
     *
     * @var Closure
     * @access private
     */
    private $lastError;
    
    /**
     * The validator service
     *
     * @var Validator
     * @access private
     */
    private $validator;
    
    /**
     * Login to the application
     *
     * @param Request $request
     * @return Response
     */
    public function loginAction(Request $request)
    {
        if (is_object($this->getUser()) && $this->getUser() instanceof User) {
            return new RedirectResponse($this->urlGenerator->generate('admin_homepage'), 302);
        }
    
        $lastError = $this->lastError;
    
        return $this->twig->render('login.html.twig', array(
            'error'         => $lastError($request),
            'last_username' => $this->session->get('_security.last_username'),
        ));
    }
  
    /**
     * Add a user account
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function newAction(Request $request)
    {
        $returnArray = array();
        // if we are already a user - then redirect back to home
        if (is_object($this->getUser()) && $this->getUser() instanceof User) {
            return new RedirectResponse($this->urlGenerator->generate('admin_homepage'), 302);
        }
        
        if ($request->getMethod() == 'POST') {
        
            $hasErrors = false;
            $returnArray['errors'] = array();
            $errors = &$returnArray['errors'];
            
            if ($request->get('password1') != $request->get('password2')) {
                $errors['password'] = 'Password must match confirmation password';
                $hasErrors = true;
            }
            
            if (!$hasErrors) {

                $user = $this->bindUser($request, true);
                
                if (!$user->isPasswordValid($request->get('password1'))) {
                    $errors['password'] = 'Password must be 6 characters or more long, contain at least one number and an upper case and lower case letter';
                    $hasErrors = true;
                }
                
                if (!$hasErrors) {

                    $returnArray['errors'] = $this->validator->validate($user);
                    
                    if (count($returnArray['errors']) == 0) {
                        $this->em->persist($user);
                        $this->em->flush();
    
                        $this->session->getFlashBag()->add('notice', 'Account created');
                        return new RedirectResponse($this->urlGenerator->generate('login'), 302);
                    }                    
                }
            } 
        }
        
        return $this->twig->render('profile/new.html.twig', $returnArray);
    }
    
    /**
     * Edit a users profile
     *
     * @param Request $request
     * @return Response 
     */
    public function editAction(Request $request)
    {
        $returnArray = array();
    
        if ($request->getMethod() == 'POST') {

            $user = $this->bindUser($request, false);
            
            $returnArray['errors'] = $this->validator->validate($user);
            
            if (count($returnArray['errors']) == 0) {
                $this->em->persist($user);
                $this->em->flush();
                
                $this->session->getFlashBag()->add('notice', 'Account updated');
            }
        }
    
        return $this->twig->render('profile/edit.html.twig', $returnArray);
    }
    
    /**
     * Change a users password
     *
     * @param Request $request
     * @return Response
     */
    public function changePasswordAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $errors = array();
            $hasErrors = false;
            $user = $this->getUser();
        
            if ($request->get('password1') != $request->get('password2')) {
                $errors['password'] = 'Password must match confirmation password';
                $hasErrors = true;
            }
            
            if (!$user->isPasswordValid($request->get('password1'))) {
                $errors['password'] = 'Password must be 6 characters or more long, contain at least one number and an upper case and lower case letter';
                $hasErrors = true;
            }
            
            if (!$hasErrors) {
            
                $encoder = $this->encoderFactory->getEncoder($user);
                $user->setPassword($encoder->encodePassword($request->get('password1'), $user->getSalt()));
                
                $this->em->persist($user);
                $this->em->flush();

                $this->session->getFlashBag()->add('notice', 'Password updated');
            } else {
                $this->session->getFlashBag()->add('passwordErrors', $errors);
            }
        }
        
        return new RedirectResponse($this->urlGenerator->generate('profile_edit'), 302);
    }
    
    /**
     * Set the encoder factory
     *
     * @param EncoderFactory $encoderFactory
     */
    public function setEncoderFactory(EncoderFactory $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }
    
    /**
     * Set the security last error
     *
     * @param Closure $lastError
     */
    public function setSecurityLastError($lastError)
    {
        $this->lastError = $lastError;
    }
    
    /**
     * Validator
     *
     * @param Validator $validator
     */
    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }
    
    /**
     * Bind a user from the request
     *
     * @param Request $request The request object
     * @param boolean $new Whether or not this is a new user . Default false
     */
    private function bindUser(Request $request, $new = false)
    {
        $user = $new? new \Expenditure\Entity\User: $this->getUser();
        
        $user->setFullname($request->get('fullname'));
        $user->setEmailAddress($request->get('emailAddress'));
        $user->setPaidDay($request->get('paidDay'));
        
        if ($new) {
            $user->setSalt(hash("sha256",time()));
            $user->setStatus(1);
            
            $encoder = $this->encoderFactory->getEncoder($user);
            $user->setPassword($encoder->encodePassword($request->get('password1'), $user->getSalt()));
        }
        
        return $user;
    } 
}