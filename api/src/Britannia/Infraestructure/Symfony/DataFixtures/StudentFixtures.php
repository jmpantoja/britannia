<?php

namespace Britannia\Infraestructure\Symfony\DataFixtures;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Employment\Situation;
use Britannia\Domain\Entity\Staff\User;
use Britannia\Domain\Entity\Student\Adult;
use Britannia\Domain\Entity\Student\Child;

use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\BankAccount;
use Britannia\Domain\VO\Employment;
use Britannia\Domain\VO\Job;
use Britannia\Domain\VO\JobStatus;
use Britannia\Domain\VO\Payment;
use Britannia\Domain\VO\PaymentMode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use PlanB\DDD\Domain\VO\CityAddress;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\Iban;
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
        $this->createMany(Adult::class, 10, function (Adult $adult, int $count) {

            $this->create($adult);

            $adult->setDni(DNI::make(...[
                $this->faker->dni()
            ]));

            $adult->setJob(Job::make(...[
                $this->faker->jobTitle(),
                JobStatus::EMPLOYED()
            ]));

        });
    }


    private function create(Student $student)
    {
        $student->setFullName(FullName::make(...[
            $this->faker->name(),
            $this->faker->lastName()
        ]));

        $student->addPhoneNumber(PhoneNumber::make(...[
            $this->faker->numerify('###-##-##-##')
        ]));

        $student->setBirthDate(
            $this->faker->dateTimeBetween('-60 years', '-18 years')
        );

        $student->setEmail(Email::make(...[
            $this->faker->email()
        ]));

        $student->setAddress(PostalAddress::make(...[
            $this->faker->address(),
            PostalCode::make(...[
                $this->faker->postcode()
            ])
        ]));

        $student->setActive($this->faker->boolean());

        $student->setPayment(Payment::make(...[
            PaymentMode::DAY_1(),
            BankAccount::make(...[
                FullName::make(...[
                    $this->faker->name(),
                    $this->faker->lastName(),
                ]),
                CityAddress::make(...[
                    'el puerto de santa maria',
                    'cádiz',
                ]),
                Iban::make('ES9820385778983000760236'),
                $this->faker->numberBetween(0, 90)
            ])
        ]));
    }
}
