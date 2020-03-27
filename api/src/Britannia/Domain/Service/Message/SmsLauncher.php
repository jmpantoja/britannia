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


use Britannia\Domain\Entity\Student\Child;
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\PhoneNumber;

abstract class SmsLauncher implements SmsLauncherInterface
{

    public function recipient(Student $student): ?string
    {
        $phoneNumber = $this->mobilePhoneNumberByStudent($student);

        if(!($phoneNumber instanceof PhoneNumber)){
            return null;
        }

        return $phoneNumber->getPhoneNumber();
    }

    public function mobilePhoneNumberByStudent(Student $student): ?PhoneNumber
    {
        $candidates = [];
        $candidates[] = $student->phoneNumbers();

        if ($student instanceof Child) {
            $candidates[] = $student->firstTutor()->phoneNumbers();
            $candidates[] = $student->secondTutor()->phoneNumbers();
        }

        $phoneNumbers = array_merge(...$candidates);

        return collect($phoneNumbers)
            ->filter(fn(PhoneNumber $phoneNumber) => $phoneNumber->isMobile())
            ->first();
    }

    public function date(): ?CarbonImmutable
    {
        return null;
    }
}
