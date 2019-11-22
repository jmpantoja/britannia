<?php

namespace Britannia\Infraestructure\Symfony\DataFixtures;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Staff\User;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Query\QueryBuilder;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\OrigamiBundle\Api\DataPersister;

class StaffMemberFixtures extends BaseFixture implements FixtureGroupInterface
{

    public function getBackupFiles(): array
    {
        return [
            sprintf('%s/dumps/britannia_other_academies.sql', __DIR__),
            sprintf('%s/dumps/britannia_schools.sql', __DIR__),
            sprintf('%s/dumps/britannia_calendar.sql', __DIR__),
            sprintf('%s/dumps/britannia_classrooms.sql', __DIR__),
            sprintf('%s/dumps/britannia_staff_members.sql', __DIR__)
        ];
    }

    public static function getGroups(): array
    {
        return ['staff'];
    }

    public function loadData(DataPersisterInterface $dataPersister): void
    {

//        $this->createMany(StaffMember::class, 2, function (StaffMember $member, int $count) {
//
//            $userName = sprintf('manager-%02d', $count);
//            $this->create($member, $userName, [
//                'ROLE_MANAGER'
//            ]);
//            return $userName;
//        });
//
//        $this->createMany(StaffMember::class, 5, function (StaffMember $member, int $count) {
//            $userName = sprintf('reception-%02d', $count);
//            $this->create($member, $userName, [
//                'ROLE_RECEPTION'
//            ]);
//            return $userName;
//        });
//
//
//        $this->createMany(StaffMember::class, 15, function (StaffMember $member, int $count) {
//            $userName = sprintf('teacher-%02d', $count);
//            $this->create($member, $userName, [
//                'ROLE_TEACHER'
//            ]);
//
//            return $userName;
//        });

    }


    public function create(StaffMember $member, string $userName, array $roles)
    {
        $member->setUserName($userName);

        $member->setFullName(FullName::make(...[
            $this->faker->firstName(),
            $this->faker->lastName()
        ]));

        $member->setPlainPassword('1234');

        $email = sprintf('%s@britannia.es', $userName);
        $member->setEmails([
            Email::make($email),
            Email::make($this->faker->email),
            Email::make($this->faker->email)

        ]);

        $member->setRoles($roles);

    }


}
