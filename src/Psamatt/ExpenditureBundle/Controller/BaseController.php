<?php

namespace Psamatt\ExpenditureBundle\Controller;

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
}