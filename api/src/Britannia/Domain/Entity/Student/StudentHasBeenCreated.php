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

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\Entity\Notification\NotificationEvent;
use Britannia\Domain\Entity\Notification\TypeOfNotification;

class StudentHasBeenCreated extends NotificationEvent
{

    public static function make(Student $student): self
    {

        $date = $student->createdAt();
        return self::builder($student)
            ->withDate($date);
    }

    public function type(): TypeOfNotification
    {
        return TypeOfNotification::NEW_STUDENT();
    }

    protected function makeSubject(): string
    {
        return sprintf('Nuevo alumno (%s)', $this->student->name());
    }


}
