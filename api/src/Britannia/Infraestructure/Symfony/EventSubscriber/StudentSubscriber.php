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


use Britannia\Domain\Entity\Student\StudentHasAttendedLesson;
use Britannia\Domain\Entity\Student\StudentHasMissedLesson;

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
        ];
    }

    public function onStudentMissedLesson(StudentHasMissedLesson $event)
    {

        die(__METHOD__);
    }

    public function onStudentAttendedLesson(StudentHasMissedLesson $event)
    {
        die(__METHOD__);
    }

}
