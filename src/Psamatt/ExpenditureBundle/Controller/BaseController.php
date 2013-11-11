<?php

namespace Psamatt\ExpenditureBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Carbon\Carbon;

class BaseController
{   
    /**
     * Get the user
     *
     * @return Psamatt\ExpenditureBundle\Entity\User|null $user
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
    
    /**
     * Add a notice to the page
     *
     * @param string $msg
     */
    public function addNotice($msg)
    {
        $this->session->getFlashBag()->add('notice', $msg);
    }
}