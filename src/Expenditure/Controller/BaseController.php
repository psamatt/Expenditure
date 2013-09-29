<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Carbon\Carbon;
use Doctrine\ORM\EntityManager;

class BaseController
{
    /**
     * Instance of EntityManager
     *
     * @access protected
     * @var EntityManager
     */
    protected $em;

    /**
     * Twig
     *
     * @access protected
     * @var Twig
     */
    protected $twig;
    
    /**
     * Security
     *
     * @access protected
     * @var Security
     */
    protected $security;
    
    /**
     * URL Generator
     *
     * @access protected
     * @var UrlGenerator
     */
    protected $urlGenerator;
    
    /**
     * Session
     *
     * @access protected
     * @var Session
     */
    protected $session;

    /**
     * Set the database object
     *
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Set the twig renderer
     *
     * @param TwigRenderer $twig
     */
    public function setTwigRenderer($twig)
    {
        $this->twig = $twig;
    }
    
    /**
     * Set security
     *
     * @param Security $security
     */
    public function setSecurity($security)
    {
        $this->security = $security;
    }
    
    /**
     * Set URL Generator
     *
     * @param UrlGenerator $urlGenerator
     */
    public function setURLGenerator(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
    
    /**
     * Set the session 
     *
     * @param Session $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }
    
    /**
     * Get the user
     *
     * @return \Spot\Entity|null $user
     */
    public function getUser()
    {
        $token = $this->security->getToken();

        if (null !== $token) {
            return $token->getUser();
        }
        
        return null;
    }

    /**
     * Get the carbon object
     *
     * @return Carbon
     */
    public function getCarbon($date = null)
    {
        return new Carbon($date);
    }
    
    /**
     * Is this entity owned by the administrator - relies on their being a user_id property on the object
     *
     * @param \Spot\Entity $entity The entity to check
     * @param string|null $getter The getter method to get the user from
     * @return boolean|RedirectResponse
     */
    public function isOwnedByAdmin($entity, $getter = null)
    {
        $getter = $getter == null? 'getUser': $getter;
    
        if (null !== $entity->{$getter}()) {
            
            if (null !== $user = $this->getUser()) {
                if ($user == $entity->{$getter}()) {
                    return true;
                }
            }
        }
        
        // error as user to trying to modify someone elses record
        throw new NotFoundHttpException("Page not found");
    }
}