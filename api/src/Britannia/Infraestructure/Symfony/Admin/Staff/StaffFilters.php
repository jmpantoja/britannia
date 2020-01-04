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


use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

final class StaffFilters extends AdminFilter
{
    public function configure()
    {
//        $this->add('active', null, [
//            'label' => 'Activo'
//        ]);
//
//        $this->add('teacher', null, [
//            'label' => 'Profesor'
//        ]);

        $this->add('fullName', 'doctrine_orm_callback', [
            'label' => 'Nombre',
            'callback' => $this->fullNameCallback()
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

            $where = sprintf('%s.fullName.firstName like :name OR %s.fullName.lastName like :name', $alias, $alias);
            $queryBuilder
                ->andwhere($where)
                ->setParameter('name', sprintf('%%%s%%', $value['value']));
            return true;
        };
    }
}
