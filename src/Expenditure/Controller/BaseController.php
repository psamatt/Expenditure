<?php

namespace Expenditure\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Carbon\Carbon;

class BaseController
{
    /**
     * Instance of Spot
     *
     * @access protected
     * @var \Spot\Mapper
     */
    protected $db;

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
     * @param Mapper $db
     */
    public function setDB(\Spot\Mapper $db)
    {
        $this->db = $db;
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
     * @param string|null The field name to get
     * @return boolean|RedirectResponse
     */
    public function isOwnedByAdmin($entity, $field = null)
    {
        $field = $field == null? 'user_id': $field;
    
        if (null !== $entity->{$field}) {
            
            if (null !== $user = $this->getUser()) {
                if ($user->id === $entity->{$field}) {
                    return true;
                }
            }
        }
        
        // error as user to trying to modify someone elses record
        throw new NotFoundHttpException("Page not found");
    }
}