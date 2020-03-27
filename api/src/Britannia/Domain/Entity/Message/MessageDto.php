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

namespace Britannia\Domain\Entity\Message;


use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\StudentList;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\Dto;

abstract class MessageDto extends Dto
{
    public ?string $subject;

    public ?string $message;

    public StudentList $students;

    public CourseList $courses;

    public StaffMember $createdBy;

    public CarbonImmutable $schedule;

    public ?CarbonImmutable $createdAt = null;

    protected function defaults(): array
    {
        return [
            'students' => StudentList::collect(),
            'courses' => CourseList::collect(),
        ];
    }


}
