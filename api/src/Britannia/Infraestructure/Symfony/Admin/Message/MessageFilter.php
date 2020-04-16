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

namespace Britannia\Infraestructure\Symfony\Admin\Message;


use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class MessageFilter extends AdminFilter
{
    public function configure()
    {
        $this->add('subject', null, [
            'label' => 'Asunto'
        ]);

        $this->add('processed', null, [
            'label' => 'Enviado'
        ]);

        $this->add('type', 'doctrine_orm_callback', [
            'callback' => function (ProxyQuery $queryBuilder, $alias, $field, $value) {
                if (!$value['value']) {
                    return;
                }

                $where = sprintf('%s INSTANCE OF :type', $alias);
                $queryBuilder
                    ->andwhere($where)
                    ->setParameter('type', $value['value']);
                return true;
            }
        ], ChoiceType::class, [
            'choice_loader' => new CallbackChoiceLoader(function () {
                return [
                    'SMS' => 'sms',
                    'Email' => 'email',
                ];
            }),
        ]);
    }
}
