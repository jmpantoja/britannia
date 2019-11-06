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


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Repository\CalendarRepositoryInterface;
use Britannia\Domain\Repository\CourseRepositoryInterface;
use Britannia\Domain\Service\Course\TimeSheetUpdater;
use Britannia\Infraestructure\Doctrine\Repository\CourseRepository;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class UpdateTimeSheetUseCase implements UseCaseInterface
{
    /**
     * @var CourseRepository
     */
    private $repository;
    /**
     * @var CalendarRepositoryInterface
     */
    private $calendar;
    /**
     * @var TimeSheetUpdater
     */
    private $updater;

    public function __construct(TimeSheetUpdater $updater, CourseRepositoryInterface $repository)
    {
        $this->updater = $updater;
        $this->repository = $repository;
    }

    public function handle(UpdateTimeSheet $updateTimeSheet)
    {
        $course = $updateTimeSheet->getCourse();
        $this->updater->updateCourseLessons($course);
    }

}
