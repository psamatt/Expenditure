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
        $user1->setEmailAddress('matt.goodwin491@gmail.com');
        $user1->setFullname('Matt Goodwin');
        $user1->setPaidDay(12);
        $user1->setSalt('28ad6d2');
        $user1->setPassword('h8BDNpPNutwlimBH06QnayV8/35sB8R2PsQ2VRdeEDa05hgTg2Pjrigt99pPrEO8LrrcWX1c01PYOr+yJBAYYg==');
        $user1->setStatus(1);
        
        $user2 = new User();
        $user2->setEmailAddress('mgoodwin@gmail.com');
        $user2->setFullname('Matt SSSdwin');
        $user2->setPaidDay(0);
        $user2->setSalt('28ad6d2');
        $user2->setPassword('h8BDNpPNutwlimBH06QnayV8/35sB8R2PsQ2VRdeEDa05hgTg2Pjrigt99pPrEO8LrrcWX1c01PYOr+yJBAYYg==');
        $user2->setStatus(1);

        $manager->persist($user1);
        $manager->persist($user2);
        
        $manager->flush();
    }
}