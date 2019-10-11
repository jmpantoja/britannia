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


use PlanB\DDD\Domain\VO\Exception\InvalidDNIFormatException;
use PlanB\DDD\Domain\VO\Exception\InvalidDNILetterException;
use PlanB\DDD\Domain\VO\Iban;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;


class IbanType extends AbstractSingleType
{


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required_message' => 'El iban es requerido'
        ]);
    }


    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return Iban::buildConstraint($options);
    }

    public function customMapping($data)
    {
        return Iban::make($data);
    }
}
