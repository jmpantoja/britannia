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

namespace Britannia\Domain\Service\Message;


use Britannia\Domain\Entity\Student\Student;
use PlanB\DDD\Domain\VO\PhoneNumber;

interface SmsLauncherInterface extends DeliveryInterface
{
    public function send(Student $student, string $message): bool;

    public function mobilePhoneNumberByStudent(Student $student): ?PhoneNumber;
}
