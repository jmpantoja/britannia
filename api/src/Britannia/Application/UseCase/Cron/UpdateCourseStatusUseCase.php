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
use Britannia\Domain\Repository\CourseRepositoryInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

final class UpdateCourseStatusUseCase implements UseCaseInterface
{
    /**
     * @var CourseRepositoryInterface
     */
    private CourseRepositoryInterface $repository;
    /**
     * @var DataPersisterInterface
     */
    private DataPersisterInterface $persister;

    public function __construct(CourseRepositoryInterface $repository, DataPersisterInterface $persister)
    {
        $this->repository = $repository;
        $this->persister = $persister;
    }

    public function handle(UpdateCourseStatus $command)
    {
        $courses = $this->repository->findCoursesForUpdateStatus();

        $total = count($courses);

        foreach ($courses as $course) {
            $course->updateStatus();
            dump($total--);
        }
    }

}
