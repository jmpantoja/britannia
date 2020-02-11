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
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class StaffDataSource extends AdminDataSource
{
    public function __invoke(StaffMember $staffMember)
    {
        $data['Profesor'] = $this->parse($staffMember->isTeacher());
        $data['Usuario'] = $this->parse($staffMember->getUsername());
        $data['DNI'] = $this->parse($staffMember->dni());
        $data['Nombre'] = $this->parse($staffMember->fullName());
        $data['Dirección'] = $this->parse($staffMember->address());
        $data['Emails'] = $this->parse($staffMember->emails());
        $data['Teléfonos'] = $this->parse($staffMember->phoneNumbers());
        $data['Cursos'] = $this->parse($staffMember->courses());
        $data['Roles'] = $this->parseRoles($staffMember);

        return $data;
    }

    private function parseRoles(StaffMember $staffMember): string
    {
        $roles = collect($staffMember->getRoles())
            ->map(function (string $role) {
                return str_replace('ROLE_', '', $role);
            })
            ->filter(fn(string $role) => $role != 'SONATA_ADMIN');

        return $this->parse($roles);

    }

}
