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


use Britannia\Domain\Entity\Notification\NotificationEvent;
use Britannia\Domain\Entity\Notification\TypeOfNotification;

final class IssueHasBeenCreated extends NotificationEvent
{

    private string $issueSubject;

    public static function make(Issue $issue): self
    {
        return self::builder($issue->student())
            ->withIssueSubject($issue->subject());
    }

    public function type(): TypeOfNotification
    {
        return TypeOfNotification::INVOICE_PAID();
    }

    protected function makeSubject(): string
    {
        return sprintf('Nueva observación sobre <b>%s</b>. %s', ...[
            $this->student->name(),
            $this->issueSubject
        ]);
    }

    private function withIssueSubject(string $invoiceSubject): self
    {
        $this->issueSubject = $invoiceSubject;
        return $this;
    }

}
