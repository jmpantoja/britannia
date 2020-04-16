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

namespace PlanB\DDDBundle\Symfony\Form\Type;


use PlanB\DDD\Domain\VO\RefundPrice;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;


class RefundPriceType extends AbstractSingleType
{

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'post_icon' => 'fa fa-euro',
            'data_class' => RefundPrice::class
        ]);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return RefundPrice::buildConstraint($options);
    }

    public function customMapping($data)
    {
        if ($data instanceof RefundPrice) {
            return $data;
        }

        return RefundPrice::make((float)$data);
    }
}
