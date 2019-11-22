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


use Britannia\Application\UseCase\Course\UpdateTimeTable;
use Britannia\Application\UseCase\StaffMember\UpdatePassword;
use Britannia\Domain\Entity\Course\TimeTabletHasChanged;
use Britannia\Domain\Entity\Staff\PasswordHasChanged;

class CourseSubscriber extends DomainEventSubscriber
{

    /**
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            TimeTabletHasChanged::class => 'onTimeSheetChanged'
        ];
    }

    public function onTimeSheetChanged(TimeTabletHasChanged $event)
    {
        $command = UpdateTimeTable::fromEvent($event);

        $this->handle($command);
    }

}
