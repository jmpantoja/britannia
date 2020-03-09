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


use Britannia\Application\UseCase\Record\CreateNotification;
use Britannia\Application\UseCase\Student\StudentJoinToCourse;
use Britannia\Domain\Entity\Notification\NotificationEventInterface;
use Britannia\Domain\Entity\Record\BorrameEvent;
use Britannia\Domain\Entity\Student\StudentHasBeenCreated;
use Britannia\Domain\Entity\Student\StudentHasJoinedToCourse;
use Britannia\Domain\Entity\Student\StudentHasLeavedCourse;
use Britannia\Domain\Entity\Student\StudentHasMissedLesson;


class NotificationSubscriber extends DomainEventSubscriber
{

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            StudentHasJoinedToCourse::class => 'onCreateNotification',
            StudentHasLeavedCourse  ::class => 'onCreateNotification',
            StudentHasBeenCreated::class => 'onCreateNotification',
            StudentHasMissedLesson::class => 'onCreateNotification'
        ];
    }

    public function onCreateNotification(NotificationEventInterface $event)
    {
        $command = CreateNotification::fromEvent($event);
        $this->handle($command);
    }


}
