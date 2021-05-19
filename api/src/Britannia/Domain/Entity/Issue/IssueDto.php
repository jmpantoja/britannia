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


use Britannia\Domain\Entity\Staff\StaffList;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Model\Dto;

final class IssueDto extends Dto
{
    public ?IssueId $id = null;

    public bool $main = false;

    public ?string $subject;

    public ?string $message;

    public StaffMember $author;

    public ?Student $student;

    public StaffList $issueHasRecipients;

    public ?CarbonImmutable $createdAt;


    protected function defaults(): array
    {
        return [
            'issueHasRecipients' => StaffList::collect(),
            'createdAt' => CarbonImmutable::now(),
        ];
    }
}
