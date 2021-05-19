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

namespace Britannia\Infraestructure\Symfony\Admin\Issue\Filter;


use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\AdminBundle\Form\Type\Filter\DefaultType;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

final class IssueStatusFilter extends Filter
{


    /**
     * @inheritDoc
     */
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $value)
    {
        $input = $value['value'];

        if (empty($input)) {
            return;
        }

        $builder = $queryBuilder->getQueryBuilder();

        $status = $input['status'] ?? 0;
        $recipient = $input['recipient'] ?? 0;

        $join = sprintf('%s.issueHasRecipients', $alias);
        $builder->leftJoin($join, 'P');

        if($status === 1 ){
            //no leidos
            $builder->andWhere('P.readAt is null');
        }

        if($status === 2 ){
            //leidos
            $builder->andWhere('P.readAt is not null');
        }

        if ($recipient === 0) {
            //Todos
            $builder->andWhere(sprintf('%s.author = :user OR P.recipient = :user', $alias));
        }

        if ($recipient === 1) {
            //Creados por mi
            $builder->andWhere(sprintf('%s.author = :user', $alias));
        }

        if ($recipient === 2) {
            //Dirigidos a mi
            $builder->andWhere('P.recipient = :user');
        }

        $user = $this->getOption('user');
        if ($recipient === 3 and $user->isManager()) {
            //Todos los usuarios
            return;
        }

        $builder->setParameter('user', $user);
    }

    /**
     * @inheritDoc
     */

    public function getDefaultOptions()
    {
        return [
            'user' => null,
            'field_type' => IssueFormType::class,
            'operator_type' => HiddenType::class,
            'operator_options' => [],
        ];
    }

    public function getRenderSettings()
    {
        return [DefaultType::class, [
            'field_type' => $this->getFieldType(),
            'field_options' => $this->getFieldOptions(),
            'operator_type' => $this->getOption('operator_type'),
            'operator_options' => $this->getOption('operator_options'),
            'label' => $this->getLabel(),
        ]];
    }

    public function getFieldOptions()
    {
        return [
            'required' => false
        ];

    }
}
