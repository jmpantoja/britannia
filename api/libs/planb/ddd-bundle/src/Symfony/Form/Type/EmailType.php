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


use PlanB\DDD\Domain\VO\Email;

use PlanB\DDD\Domain\VO\Validator\Constraint;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EmailType extends AbstractSingleType
{


    public function customOptions(OptionsResolver $resolver)
    {

    }

    /**
     * @param array $options
     * @return null|Constraint
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return Email::buildConstraint($options);
    }

    public function customMapping($data)
    {
        return Email::make($data);
    }
}
