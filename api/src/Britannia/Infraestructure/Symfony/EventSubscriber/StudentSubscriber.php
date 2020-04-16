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

namespace Britannia\Infraestructure\Symfony\EventSubscriber;


use Britannia\Application\UseCase\Invoice\CreateInvoice;
use Britannia\Application\UseCase\Lesson\LessonHasBeenAttended;
use Britannia\Application\UseCase\Student\StudentUpdatedStatus;
use Britannia\Domain\Entity\Student\StudentHasAttendedLesson;
use Britannia\Domain\Entity\Student\StudentHasJoinedToCourse;
use Britannia\Domain\Entity\Student\StudentHasLeavedCourse;
use Britannia\Domain\Entity\Student\StudentHasMissedLesson;
use Carbon\CarbonImmutable;

class StudentSubscriber extends DomainEventSubscriber
{
    /**
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            StudentHasMissedLesson::class => 'onStudentMissedLesson',
            StudentHasAttendedLesson::class => 'onStudentAttendedLesson',
            StudentHasJoinedToCourse::class => 'onStudentJoinToCourse',
            StudentHasLeavedCourse::class => 'onStudentLeaveACourse',
        ];
    }

    public function onStudentJoinToCourse(StudentHasJoinedToCourse $event)
    {
        $command = StudentUpdatedStatus::make($event->student(), $event->course());
        $this->handle($command);

        $command = CreateInvoice::update($event->student(), $event->course(), CarbonImmutable::now());
        $this->handle($command);

    }

    public function onStudentLeaveACourse(StudentHasLeavedCourse $event)
    {
        $command = StudentUpdatedStatus::make($event->student(), $event->course());
        $this->handle($command);
    }

    public function onStudentMissedLesson(StudentHasMissedLesson $event)
    {
    }

    public function onStudentAttendedLesson(StudentHasAttendedLesson $event)
    {
        $command = LessonHasBeenAttended::make($event->attendance());
        $this->handle($command);
    }

}
