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
use Britannia\Domain\Entity\Notification\Notification;
use Britannia\Domain\Entity\Staff\StaffMember;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use Symfony\Component\Security\Core\Security;

class CreateNotificationUseCase implements UseCaseInterface
{
    /**
     * @var Security
     */
    private $security;
    /**
     * @var DataPersisterInterface
     */
    private $persister;

    private $defaultUser;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Security $security,
                                DataPersisterInterface $persister,
                                EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->persister = $persister;
        $this->entityManager = $entityManager;
    }

    public function handle(CreateNotification $command)
    {
        $user = $this->security->getUser();
        if (!($user instanceof StaffMember)) {
            return;
        }

        $author = $this->ensureEntityIsManaged($user);

        $dto = $command->dto();
        $dto->author = $author;

        $record = Notification::make($dto);

        $this->persister->persist($record);
    }


    protected function ensureEntityIsManaged(StaffMember $entity)
    {
        if ($this->entityManager->contains($entity)) {
            return $entity;
        }

        $managed = $this->entityManager->find(get_class($entity), $entity->id());

        if (!is_null($managed)) {
            return $managed;
        }

        $this->persister->persist($entity);
        return $entity;
    }
}
