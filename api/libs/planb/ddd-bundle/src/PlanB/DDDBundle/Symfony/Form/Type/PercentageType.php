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


use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PercentageType extends AbstractSingleType
{

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'post_icon' => 'fa fa-percent',
            'data_class' => Percent::class,
            'attr' => [
                'style' => 'width:60px;'
            ]
        ]);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return Percent::buildConstraint($options);
    }

    public function customMapping($data)
    {

        return Percent::make((int)$data);
    }

}
