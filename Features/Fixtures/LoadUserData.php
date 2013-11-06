<?php

namespace Fixtures;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

use Psamatt\ExpenditureBundle\Entity\User;

/**
 * Load user data for Behat features
 *
 */
class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {    
        $user1 = new User();
        $user1->update('Matt Goodwin', 'matt.goodwin491@gmail.com', 12);
        $user1->updateSecurityDetails('h8BDNpPNutwlimBH06QnayV8/35sB8R2PsQ2VRdeEDa05hgTg2Pjrigt99pPrEO8LrrcWX1c01PYOr+yJBAYYg==', '28ad6d2');
        
        $user2 = new User();
        $user2->update('Matt SSSdwin', 'mgoodwin@gmail.com', 0);
        $user2->updateSecurityDetails('h8BDNpPNutwlimBH06QnayV8/35sB8R2PsQ2VRdeEDa05hgTg2Pjrigt99pPrEO8LrrcWX1c01PYOr+yJBAYYg==', '28ad6d2');

        $manager->persist($user1);
        $manager->persist($user2);
        
        $manager->flush();
    }
}