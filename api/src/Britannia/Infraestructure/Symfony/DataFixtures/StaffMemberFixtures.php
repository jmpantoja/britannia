<?php

namespace Britannia\Infraestructure\Symfony\DataFixtures;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;

use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Staff\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use PlanB\OrigamiBundle\Api\DataPersister;

class StaffMemberFixtures extends BaseFixture
{


//    /**
//     * @var DataPersister
//     */
//    private $dataPersister;
//
//    public function __construct(DataPersisterInterface $dataPersister)
//    {
//        $this->dataPersister = $dataPersister;
//    }
//
//    public function load(ObjectManager $manager)
//    {
//        $user = new StaffMember();
//        $user->setUserName('manager');
//
//        $user->setFirstName('pepe');
//        $user->setLastName('botika');
//
//        $user->setPlainPassword('1234');
//        $user->setPassword('1234');
//        $user->setEmail('manager@britannia.es');
//        $user->setActive(true);
//
//        $user->setRoles([
//            'ROLE_MANAGER'
//        ]);
//
//        $this->dataPersister->persist($user);
//
//
////        for ($i = 0; $i < 1; $i++) {
////            $user = new StaffMember();
////
////            $name = sprintf('user-%02d', $i + 1);
////            $email = sprintf('%s@britannia.es', $name);
////
////            $user->setUserName($name);
////
////            $user->setFirstName($name);
////            $user->setLastName('britannia');
////
////            $user->setPlainPassword('1234');
////
////
////            $user->setEmail($email);
////            $user->setActive(true);
////
////            $this->dataPersister->persist($user);
////
////        }
//
//    }

    public function loadData(DataPersisterInterface $dataPersister): void
    {
        $this->createMany(StaffMember::class, 2, function (StaffMember $member, int $count) {

            $userName = sprintf('manager-%02d', $count);
            $this->create($member, $userName, [
                'ROLE_MANAGER'
            ]);
            return $userName;
        });

        $this->createMany(StaffMember::class, 5, function (StaffMember $member, int $count) {
            $userName = sprintf('reception-%02d', $count);
            $this->create($member, $userName, [
                'ROLE_RECEPTION'
            ]);
            return $userName;
        });


        $this->createMany(StaffMember::class, 15, function (StaffMember $member, int $count) {
            $userName = sprintf('teacher-%02d', $count);
            $this->create($member, $userName, [
                'ROLE_TEACHER'
            ]);

            return $userName;
        });

    }

    public function create(StaffMember $member, string $userName, array $roles)
    {
        $member->setUserName($userName);

        $member->setFirstName($this->faker->firstName());
        $member->setLastName($this->faker->lastName());

        $member->setPlainPassword('1234');

        $email = sprintf('%s@britannia.es', $userName);
        $member->setEmail($email);
        $member->setActive(true);

        $member->setRoles($roles);

    }
}
