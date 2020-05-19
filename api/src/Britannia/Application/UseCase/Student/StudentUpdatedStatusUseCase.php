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

namespace Britannia\Application\UseCase\Student;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

final class StudentUpdatedStatusUseCase implements UseCaseInterface
{
    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    public function handle(StudentUpdatedStatus $command)
    {
        $command->student()->updateStatus();
        $course = $command->course();
        $this->entityManager->refresh($course);

        $course->updateNumOfStudents();
    }
}
