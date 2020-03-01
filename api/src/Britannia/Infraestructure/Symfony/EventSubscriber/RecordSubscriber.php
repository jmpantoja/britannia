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


use Britannia\Application\UseCase\Record\AddRecord;
use Britannia\Application\UseCase\Student\StudentJoinToCourse;
use Britannia\Domain\Entity\Record\BorrameEvent;
use Britannia\Domain\Entity\Record\RecordEventInterface;
use Britannia\Domain\Entity\Student\StudentHasAttendedLesson;
use Britannia\Domain\Entity\Student\StudentHasBeenCreated;
use Britannia\Domain\Entity\Student\StudentHasJoinedToCourse;
use Britannia\Domain\Entity\Student\StudentHasLeavedCourse;
use Britannia\Domain\Entity\Student\StudentHasMissedLesson;


class RecordSubscriber extends DomainEventSubscriber
{

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            StudentHasJoinedToCourse::class => 'onUpdateRecord',
            StudentHasLeavedCourse  ::class => 'onUpdateRecord',
            StudentHasBeenCreated::class => 'onUpdateRecord',
            StudentHasMissedLesson::class => 'onUpdateRecord'
        ];
    }

    public function onUpdateRecord(RecordEventInterface $event)
    {
        $command = AddRecord::fromEvent($event);
        $this->handle($command);
    }


}
