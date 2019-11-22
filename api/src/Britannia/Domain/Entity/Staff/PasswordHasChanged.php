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


use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Event\DomainEvent;


class PasswordHasChanged extends DomainEvent
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
        $this->occurredOn = CarbonImmutable::now();
        $this->staffMember = $staffMember;
        $this->newPassword = $newPassword;
    }

    /**
     * @return CarbonImmutable
     */
    public function occurredOn(): CarbonImmutable
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
