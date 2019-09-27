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
use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Exceptions\ValidationException;

use Symfony\Component\DependencyInjection\Tests\Compiler\D;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;


class DNIType extends AbstractSingleType implements DataTransformerInterface
{


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'planb.dni';
    }

    /**
     *
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($value)
    {

        return (string)$value;
    }


    /**
     *
     * @param mixed $value The value in the transformed representation
     *
     * @return mixed The value in the original representation
     *
     * @throws \ReflectionException
     */
    public function reverseTransform($value)
    {

        return $this->resolve($value, function($value){
            return DNI::make($value);
        });


    }

    protected function getRequiredErrorMessage(): string
    {
        return 'El dni es requerido';
    }
}
