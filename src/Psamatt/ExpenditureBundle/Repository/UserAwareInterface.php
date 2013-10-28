<?php

namespace Psamatt\ExpenditureBundle\Repository;

interface UserAwareInterface
{
    /**
     * Is this record owned by the administrator
     *
     * @param object $item
     * @param object $user
     * @return boolean Whether or not the item is owner by the administrator
     */
    function isOwnedByAdmin($item, $user);
}