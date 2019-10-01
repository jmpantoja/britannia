<?php

namespace Britannia\Infraestructure\Symfony\DataFixtures;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Employment\Situation;
use Britannia\Domain\Entity\Staff\User;
use Britannia\Domain\Entity\Student\Adult;
use Britannia\Domain\Entity\Student\Child;

use Britannia\Domain\VO\Employment;
use Britannia\Domain\VO\Job;
use Britannia\Domain\VO\JobStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PostalAddress;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\PostalCode;
use PlanB\OrigamiBundle\Api\DataPersister;
use Sonata\AdminBundle\Form\Type\AdminType;

class StudentFixtures extends BaseFixture
{

    public function loadData(DataPersisterInterface $dataPersister): void
    {
        $this->createMany(Adult::class, 20, function (Adult $adult, int $count) {

            $adult->setFullName(FullName::make(...[
                $this->faker->name(),
                $this->faker->lastName()
            ]));

            $adult->addPhoneNumber(PhoneNumber::make(...[
                $this->faker->phoneNumber()
            ]));

            $adult->setBirthDate(
                $this->faker->dateTimeBetween('-60 years', '-18 years')
            );

            $adult->setEmail(Email::make(...[
                $this->faker->email()
            ]));

            $adult->setAddress(PostalAddress::make(...[
                $this->faker->address(),
                PostalCode::make(...[
                    $this->faker->postcode()
                ])
            ]));;
            $adult->setDni(DNI::make(...[
                $this->faker->dni()
            ]));

            $adult->setActive($this->faker->boolean());

            $adult->setJob(Job::make($this->faker->jobTitle(), JobStatus::EMPLOYED()));

        });
    }
}
