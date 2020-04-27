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


use Britannia\Domain\VO\StaffMember\Status;
use Britannia\Infraestructure\Symfony\Service\Security\RoleService;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class StaffFilters extends AdminFilter
{

    /**
     * @var RoleService
     */
    private RoleService $rolService;

    public function setRolService(RoleService $roleService): self
    {
        $this->rolService = $roleService;
        return $this;
    }

    public function configure()
    {
        $this->add('fullName', 'doctrine_orm_callback', [
            'label' => 'Nombre',
            'callback' => $this->fullNameCallback()
        ]);

        $this->add('rol', 'doctrine_orm_callback', [
            'label' => 'Rol',
            'field_type' => ChoiceType::class,
            'field_options' => [
                'label' => 'Rol',
                'choices' => $this->rolService->getList(),
                'placeholder' => 'Todos'
            ],
            'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {
                if (!$value['value']) {
                    return;
                }
                $where = sprintf('%s.roles like :rolname ', $alias, $alias);
                $queryBuilder
                    ->andwhere($where)
                    ->setParameter('rolname', sprintf('%%%s%%', $value['value']));

                return true;
            }
        ]);


        $this->add('status', 'doctrine_orm_callback', [
            'label' => 'Estado',
            'field_type' => ChoiceType::class,
            'field_options' => [
                'label' => 'Rol',
                'choices' => array_flip(Status::getConstants()),
                'placeholder' => 'Todos'
            ],
            'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {
                if (!$value['value']) {
                    return;
                }
                $where = sprintf('%s.status = :status ', $alias, $alias);
                $queryBuilder
                    ->andwhere($where)
                    ->setParameter('status', $value['value']);

                return true;
            }
        ]);

    }

    /**
     * @return \Closure
     */
    protected function fullNameCallback(): \Closure
    {
        return function (ProxyQuery $queryBuilder, $alias, $field, $value) {
            if (!$value['value']) {
                return;
            }

            $where = sprintf('%s.fullName.fullName like :name', $alias, $alias);
            $queryBuilder
                ->andwhere($where)
                ->setParameter('name', sprintf('%%%s%%', $value['value']));
            return true;
        };
    }
}
