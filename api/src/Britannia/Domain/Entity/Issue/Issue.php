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
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;

class Issue
{
    use AggregateRootTrait;

    /** @var ?IssueId */
    private $id;

    private $main = false;

    /** @var ?string */
    private $subject;

    /** @var ?string */
    private $message;

    /** @var ?StaffMember */
    private $author;

    /** @var ?Student */
    private $student;

    /** @var Collection */
    private $issueHasRecipients;

    /** @var CarbonImmutable */
    private $createdAt;


    public static function make(IssueDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(IssueDto $dto)
    {
        $this->id = new IssueId();

        $this->main = $dto->main;
        $this->createdAt = $dto->createdAt ?? CarbonImmutable::now();
        $this->issueHasRecipients = new ArrayCollection();

        $this->update($dto);
    }

    public function update(IssueDto $dto): self
    {
        $this->author = $dto->author;
        $this->student = $dto->student;
        $this->subject = $dto->subject;
        $this->message = $dto->message;

        $this->setRecipients($dto->issueHasRecipients);

        $this->notify(IssueHasBeenCreated::make($this));
        return $this;
    }

    public function setRecipients(StaffList $issueHasRecipients): self
    {
        $this->recipientList()
            ->toRecipientList()
            ->forRemovedItems($issueHasRecipients, [$this, 'removeRecipient'])
            ->forAddedItems($issueHasRecipients, [$this, 'addRecipient']);

        return $this;
    }

    public function addRecipient(StaffMember $recipient): self
    {
        $joined = IssueRecipient::make($this, $recipient);

        $this->recipientList()->add($joined);

        return $this;
    }

    public function removeRecipient(StaffMember $recipient): self
    {
        $joined = IssueRecipient::make($this, $recipient);
        $this->recipientList()->remove($joined);

        return $this;
    }

    public function containsRecipient(StaffMember $user): bool
    {
        return $this->recipientList()->contains($user);
    }

    public function equalAuthor(StaffMember $author): bool
    {
        return $this->author()->equals($author);
    }

    public function toggleReadStateByUser(StaffMember $user): self
    {
        $this->recipientList()
            ->toggleReadStateByUser($user);

        return $this;
    }

    public function hasBeenReadByUser(StaffMember $user): bool
    {
        return $this->recipientList()->hasBeenReadByUser($user);
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function subject()
    {
        return $this->subject;
    }

    /**
     * @return bool
     */
    public function isMain(): bool
    {
        return $this->main;
    }

    /**
     * @return mixed
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function author(): StaffMember
    {
        return $this->author;
    }

    /**
     * @return mixed
     */
    public function student()
    {
        return $this->student;
    }

    /**
     * @return array
     */
    public function issueHasRecipients(): array
    {
        return $this->recipientList()->toArray();
    }


    private function recipientList(): IssueRecipientList
    {
        return IssueRecipientList::collect($this->issueHasRecipients);
    }

    /**
     * @return CarbonImmutable
     */
    public function createdAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function __toString(){
        return $this->subject;
    }

}
