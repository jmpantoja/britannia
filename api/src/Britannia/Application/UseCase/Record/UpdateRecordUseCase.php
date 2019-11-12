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
use PlanB\DDD\Application\UseCase\UseCaseInterface;
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

    public function __construct(Security $security, DataPersisterInterface $persister)
    {

        $this->security = $security;
        $this->persister = $persister;
    }

    public function handle(UpdateRecord $command)
    {
        $student = $command->getStudent();
        $createdBy = $this->security->getUser();
        $description = $command->getDescription();

        $record = Record::make($student, $createdBy, $description);

        $this->persister->persist($record);
    }

}
