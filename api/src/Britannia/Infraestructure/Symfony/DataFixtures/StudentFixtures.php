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

class StudentFixtures extends Fixture
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


        for ($i = 0; $i < 1; $i++) {
            $adult = new Adult();

            $adult->setFullName(FullName::make('pepe', 'lopez'));

            $adult->addPhoneNumber(PhoneNumber::make('999 12 31 23'));

            $adult->setBirthDate(new \DateTime('03-06-1975'));

            $adult->setEmail(Email::make('pepe@botika.es'));

            $adult->setAddress(PostalAddress::make('calle del arroyo, num 12', PostalCode::make('11500')));

            $adult->setDni(DNI::make('17346689C'));
//
//
//            $adult->setJob(Job::make('artista', JobStatus::EMPLOYED() ));
//
//            $adult->setActive(true);

            $this->dataPersister->persist($adult);
        }

//
//        for ($i = 0; $i < 1; $i++) {
//            $child = new Child();
//
//
//            $child->setFullName(FullName::make('child', 'niño'));
//
//            $child->addPhoneNumber(PhoneNumber::make('999 12 31 23'));
//            $child->setBirthDate(new \DateTimeImmutable('03-06-2010'));
//            $child->setEmail(Email::make('pepe@botika.es'));
//
//            $child->setAddress(PostalAddress::make('calle del arroyo, num 12', PostalCode::make('11500')));
//
//            $child->setActive(true);
//
//            $this->dataPersister->persist($child);
//        }

    }
}
