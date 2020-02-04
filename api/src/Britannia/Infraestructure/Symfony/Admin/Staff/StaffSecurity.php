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

namespace Britannia\Infraestructure\Symfony\Admin\Staff;


use Britannia\Domain\Entity\Staff\StaffMember;
use PlanB\DDDBundle\Sonata\Admin\AdminSecurity;

final class StaffSecurity extends AdminSecurity
{

    const EDIT = 'edit';

    public static function make(\Symfony\Component\Security\Core\Security $security): self
    {
        return new self($security);
    }

    public function isEditAllowed(StaffMember $object)
    {
        return $this->currentUser()->equals($object);
    }

    public function isAllowed(string $action, ?StaffMember $object)
    {
        if (is_null($object)) {
            return false;
        }

        if (self::EDIT === $action) {
            return $this->isEditAllowed($object);
        }

        return false;
    }


}
