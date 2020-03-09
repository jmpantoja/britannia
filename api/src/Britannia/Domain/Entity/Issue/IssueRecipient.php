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


use Britannia\Domain\Entity\Staff\StaffMember;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;

final class IssueRecipient implements Comparable
{
    use AggregateRootTrait;
    use ComparableTrait;

    /** @var ?IssueRecipientId */
    private $id;

    /** @var Issue */
    private $issue;

    /** @var StaffMember */
    private $recipient;

    /** @var CarbonImmutable */
    private $readAt;

    public static function make(Issue $issue, StaffMember $recipient): self
    {
        return new self($issue, $recipient);
    }

    private function __construct(Issue $issue, StaffMember $recipient)
    {
        $this->id = new IssueRecipientId();
        $this->issue = $issue;
        $this->recipient = $recipient;
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return Issue
     */
    public function issue(): Issue
    {
        return $this->issue;
    }

    /**
     * @return StaffMember
     */
    public function recipient(): StaffMember
    {
        return $this->recipient;
    }

    /**
     * @return CarbonImmutable
     */
    public function readAt(): CarbonImmutable
    {
        return $this->readAt;
    }

    public function hash(): string
    {
        return sprintf('%s-%s', ...[
            $this->issue->id(),
            $this->recipient->id(),
        ]);
    }

}
