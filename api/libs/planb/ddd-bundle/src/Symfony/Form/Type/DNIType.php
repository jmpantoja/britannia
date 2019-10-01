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


use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\Exception\InvalidDNIFormatException;
use PlanB\DDD\Domain\VO\Exception\InvalidDNILetterException;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class DNIType extends AbstractSingleType implements DataTransformerInterface
{


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required_message' => 'El DNI es requerido'
        ]);
    }


    public function customMapping(FormDataMapper $mapper)
    {
        $mapper
            ->try(function ($value) {
                return DNI::make($value);
            });
    }


}
