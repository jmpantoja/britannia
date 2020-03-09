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

namespace Britannia\Domain\Entity\Issue;


use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\Staff\StaffList;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Attachment\AttachmentList;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentList;
use PlanB\DDD\Domain\Model\Dto;

final class IssueDto extends Dto
{

    public ?string $subject;

    public ?string $message;

    public StaffMember $author;

    public Student $student;

    public StaffList $issueHasRecipients;

    protected function defaults(): array
    {
        return [
            'issueHasRecipients' => IssueRecipientList::collect(),
        ];
    }
}
