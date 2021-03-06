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

namespace Britannia\Application\UseCase\StaffMember;


use Britannia\Domain\Entity\Staff\PasswordWasChanged;
use Britannia\Domain\Entity\Staff\StaffMember;

class UpdatePassword
{

    /**
     * @var StaffMember
     */
    private $staffMember;
    /**
     * @var string
     */
    private $newPassword;

    public static function fromEvent(PasswordWasChanged $event)
    {
        return new self($event->getStaffMember(), $event->getNewPassword());
    }

    private function __construct(StaffMember $staffMember, string $newPassword)
    {

        $this->staffMember = $staffMember;
        $this->newPassword = $newPassword;
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
