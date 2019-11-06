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

namespace Britannia\Application\UseCase\Course;


use Britannia\Domain\Repository\CourseRepositoryInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class UpdateCourseStatusUseCase implements UseCaseInterface
{

    /**
     * @var CourseRepositoryInterface
     */
    private $repository;

    public function __construct(CourseRepositoryInterface $repository)
    {

        $this->repository = $repository;
    }

    public function handle(UpdateCourseStatus $status)
    {
        $date = $status->getDate();
        $courses = $this->repository->findUpdateStatusPending($date);

        foreach ($courses as $course){
            $course->updateStatus($date);
        }
    }
}
