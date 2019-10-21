<?php

namespace Britannia\Infraestructure\Symfony\DataFixtures;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Staff\User;
use Doctrine\DBAL\Query\QueryBuilder;
use PlanB\OrigamiBundle\Api\DataPersister;

class StaffMemberFixtures extends BaseFixture
{

    public function loadData(DataPersisterInterface $dataPersister): void
    {

//        $builder = $this->getQueryBuilder();
//        $builder->select('*')
//            ->from('user');
//
//        $this->import($builder, StaffMember::class, function (StaffMember $member, $data) {
//
//            $member->setFirstName($data['name']);
//            $member->setLastName('');
//            $member->setUserName($data['user']);
//            $member->setPlainPassword('1234');
//
//            $member->setEmail($data['email']);
//        });

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
