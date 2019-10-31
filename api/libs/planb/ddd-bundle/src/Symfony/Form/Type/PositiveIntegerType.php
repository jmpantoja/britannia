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


use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PositiveIntegerType extends AbstractSingleType
{

    public function customOptions(OptionsResolver $resolver)
    {;
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return PositiveInteger::buildConstraint($options);
    }

    public function customMapping($data)
    {
        return PositiveInteger::make((int)$data);
    }

}
