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


use Britannia\Domain\Repository\CalendarRepositoryInterface;
use Britannia\Domain\Service\Course\TimeTableUpdater;
use Britannia\Infraestructure\Doctrine\Repository\CourseRepository;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class UpdateTimeTableUseCase implements UseCaseInterface
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
     * @var TimeTableUpdater
     */
    private $updater;

    public function __construct(TimeTableUpdater $updater)
    {
        $this->updater = $updater;
    }

    public function handle(UpdateTimeTable $updateTimeSheet)
    {
        $course = $updateTimeSheet->getCourse();
        $timeTable = $updateTimeSheet->getTimeTable();

        $this->updater->updateCourseLessons($course, $timeTable);

    }

}
