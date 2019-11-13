<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Application\UseCase\Record;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Record\Record;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use PlanB\DDDBundle\Sonata\ModelManager;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Symfony\Component\Security\Core\Security;

class UpdateRecordUseCase implements UseCaseInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var DataPersisterInterface
     */
    private $persister;
    /**
     * @var StaffMemberRepositoryInterface
     */
    private $repository;

    private $defaultUser;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Security $security,
                                DataPersisterInterface $persister,
                                EntityManagerInterface $entityManager,
                                StaffMemberRepositoryInterface $repository)
    {
        $this->security = $security;
        $this->persister = $persister;
        $this->entityManager = $entityManager;
        $this->repository = $repository;

    }

    public function handle(UpdateRecord $command)
    {
        $student = $command->getStudent();
        $type = $command->getType();
        $description = $command->getDescription();
        $date = $command->getDate();

//        $createdBy = $this->security->getUser() ?? $this->repository->loadUserByUsername('cron');

        $createdBy = $this->ensureEntityIsManaged($this->security->getUser());
        $student = $this->ensureEntityIsManaged($student);

        $record = Record::make($student, $date, $type, $createdBy, $description);

        $this->persister->persist($record);
    }


    protected function ensureEntityIsManaged(object $entity)
    {
        if ($this->entityManager->contains($entity)) {
            return $entity;
        }

        $managed = $this->entityManager->find(get_class($entity), $entity->getId());

        if (!is_null($managed)) {
            return $managed;
        }

        $this->persister->persist($entity);
        return $entity;
    }
}
