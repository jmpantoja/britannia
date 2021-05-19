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

    public function contains(StaffMember $user): bool
    {
        return false !== $this->toRecipientList()
                ->indexOf($user);
    }

    public function hasBeenReadByUser(StaffMember $user): bool
    {
        return false !== $this->values()
                ->search(fn(IssueRecipient $issueRecipient) => $issueRecipient->hasBeenReadByUser($user));
    }

    public function findByRecipient(StaffMember $user)
    {
        $key = $this->toRecipientList()
            ->indexOf($user);

        if (false === $key) {
            return null;
        }
        return $this->values()->get($key);
    }

    public function toggleReadStateByUser(StaffMember $user): self
    {
        $issueRecipient = $this->findByRecipient($user);

        if ($issueRecipient instanceof IssueRecipient) {
            $issueRecipient->toggleReadState();
        }
        return $this;
    }
}
