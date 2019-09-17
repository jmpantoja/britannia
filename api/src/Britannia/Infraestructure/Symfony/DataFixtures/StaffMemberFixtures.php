<?php

namespace Britannia\Infraestructure\Symfony\DataFixtures;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Staff\Staff;

use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Staff\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use PlanB\OrigamiBundle\Api\DataPersister;

class StaffMemberFixtures extends Fixture
{

    /**
     * @var DataPersister
     */
    private $dataPersister;

    public function __construct(DataPersisterInterface $dataPersister)
    {
        $this->dataPersister = $dataPersister;
    }

    public function load(ObjectManager $manager)
    {
        $user = new StaffMember();
        $user->setUserName('admin');

        $user->setFirstName('pepe');
        $user->setLastName('botika');

        $user->setPlainPassword('1234');
        $user->setPassword('1234');
        $user->setEmail('pepe@botika.es');
        $user->setActive(true);


        $this->dataPersister->persist($user);


        for ($i = 0; $i < 1; $i++) {
            $user = new StaffMember();

            $name = sprintf('user-%02d', $i + 1);
            $email = sprintf('%s@britannia.es', $name);

            $user->setUserName($name);

            $user->setFirstName($name);
            $user->setLastName('britannia');

            $user->setPlainPassword('1234');


            $user->setEmail($email);
            $user->setActive(true);

            $this->dataPersister->persist($user);

        }

    }
}
