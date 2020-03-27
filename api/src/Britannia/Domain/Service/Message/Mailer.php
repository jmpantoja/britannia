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

abstract class Mailer implements MailerInterface
{

    public function recipient(Student $student): ?string
    {
        $email = $this->emailByStudent($student);

        if(!($email instanceof Email)){
            return null;
        }

        return $email->getEmail();
    }

    public function emailByStudent(Student $student): ?Email
    {
        $candidates = [];
        $candidates[] = $student->emails();

        if ($student instanceof Child) {
            $candidates[] = $student->firstTutor()->emails();
            $candidates[] = $student->secondTutor()->emails();
        }

        $emails = array_merge(...$candidates);

        return collect($emails)
            ->filter()
            ->first();
    }

    public function date(): ?CarbonImmutable
    {
        return null;
    }
}
