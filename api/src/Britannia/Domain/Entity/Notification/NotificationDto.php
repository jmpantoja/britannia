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

namespace Britannia\Domain\Entity\Notification;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use PlanB\DDD\Domain\Model\Dto;

final class NotificationDto extends Dto
{
    public StaffMember $author;
    public ?Student $student;
    public ?Course $course;
    public TypeOfNotification $type;
    public string $subject;
    public ?string $message = '';
}
