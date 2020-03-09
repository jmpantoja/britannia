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
use PlanB\DDD\Domain\Model\EntityList;

final class IssueRecipientList extends EntityList
{

    protected function typeName(): string
    {
        return IssueRecipient::class;
    }

    public function toRecipientList(): StaffList
    {
        $recipients = $this->values()
            ->map(fn(IssueRecipient $issueRecipient) => $issueRecipient->recipient());

        return StaffList::collect($recipients);
    }
}
