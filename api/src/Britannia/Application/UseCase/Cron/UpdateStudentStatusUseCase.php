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

namespace Britannia\Application\UseCase\Cron;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

final class UpdateStudentStatusUseCase implements UseCaseInterface
{
    private StudentRepositoryInterface $repository;
    private DataPersisterInterface $persister;

    public function __construct(StudentRepositoryInterface $repository, DataPersisterInterface $persister)
    {
        $this->repository = $repository;
        $this->persister = $persister;
    }

    public function handle(UpdateStudentStatus $command)
    {
        $this->repository->disableStudentsWithoutActiveCourses();
    }
}
