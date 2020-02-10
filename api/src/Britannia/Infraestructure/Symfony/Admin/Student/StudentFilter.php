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

namespace Britannia\Infraestructure\Symfony\Admin\Student;


use Britannia\Domain\VO\Student\PartOfDay\PartOfDay;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\SetOfSkillsType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\PartOfDayType;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class StudentFilter extends AdminFilter
{

    public function configure()
    {

        $this->add('fullName', 'doctrine_orm_callback', [
            'label' => 'Nombre',
            'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {
                if (!$value['value']) {
                    return;
                }

                $where = sprintf('%s.fullName.firstName like :name OR %s.fullName.lastName like :name', $alias, $alias);
                $queryBuilder
                    ->andwhere($where)
                    ->setParameter('name', sprintf('%%%s%%', $value['value']));

                return true;
            }
        ]);

        $this->add('active', null, [
            'label' => 'Activo',
            'show_filter' => true
        ]);

        $this->add('Cumple', 'doctrine_orm_callback', [
            'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {
                if (!$value['value']) {
                    return;
                }

                $where = sprintf('%s.birthMonth = :month', $alias);
                $queryBuilder
                    ->andwhere($where)
                    ->setParameter('month', $value['value']);
                return true;
            }
        ], ChoiceType::class, [

            'choice_loader' => new CallbackChoiceLoader(function () {
                return [
                    ''=>'',
                    'Enero' => 1,
                    'Febrero' => 2,
                    'Marzo' => 3,
                    'Abril' => 4,
                    'Mayo' => 5,
                    'Junio' => 6,
                    'Julio' => 7,
                    'Agosto' => 8,
                    'Septiembre' => 9,
                    'Octubre' => 10,
                    'Noviembre' => 11,
                    'Diciembre' => 12,
                ];
            }),
        ]);

    }
}
