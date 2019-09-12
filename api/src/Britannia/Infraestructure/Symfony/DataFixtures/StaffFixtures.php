<?php

namespace Britannia\Infraestructure\Symfony\DataFixtures;

use Britannia\Domain\Entity\Staff\Staff;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StaffFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $staffMember = new Staff();
        $staffMember->setUserName('admin');

        $staffMember->setFirstName('pepe');
        $staffMember->setLastName('botika');

        $staffMember->setPassword('1234');
        $staffMember->setEmail('pepe@botika.es');
        $staffMember->setActive(true);

        $manager->persist($staffMember);

        $manager->flush();
    }
}
