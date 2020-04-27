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

namespace Britannia\Infraestructure\Symfony\Service\Security;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Class RoleService
 * @package Britannia\Infraestructure\Symfony\Service\Security
 */
class RoleService
{

    /**
     * @var array
     */
    private $roles = [];


    public function __construct(ParameterBagInterface $params)
    {
        $roles = $params->get('security.role_hierarchy.roles');


        $list = $this->buildList($roles);

        $this->roles = $list;
    }

    /**
     * @param array $hierarchy
     * @return string[]
     */
    private function buildList(array $hierarchy): array
    {

        $list = [];
        unset($hierarchy['ROLE_SUPER_ADMIN']);
        $roles = array_keys($hierarchy);

        foreach ($roles as $role) {
            $label = $this->beautify($role);
            $list[$label] = $role;
        }

        return $list;
    }

    private function beautify(string $role): string
    {
        $role = strtolower($role);
        $role = preg_replace(['/^role_(.*)$/', '/_+/'], ['$1', ' '], $role);

        return ucwords($role);
    }

    public function getList(): array
    {
        return $this->roles;
    }

}
