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

namespace Britannia\Domain\Entity\Staff;


use PlanB\DDD\Domain\Event\DomainEvent;


class PasswordWasChanged extends DomainEvent
{
    private $occurredOn;
    /**
     * @var StaffMember
     */
    private $staffMember;
    /**
     * @var string
     */
    private $newPassword;

    public function __construct(StaffMember $staffMember, string $newPassword)
    {
        $this->occurredOn = new \DateTimeImmutable();
        $this->staffMember = $staffMember;
        $this->newPassword = $newPassword;
    }

    /**
     * @return \DateTime
     */
    public function occurredOn(): \DateTime
    {
        return $this->occurredOn;
    }

    /**
     * @return StaffMember
     */
    public function getStaffMember(): StaffMember
    {
        return $this->staffMember;
    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }


}
