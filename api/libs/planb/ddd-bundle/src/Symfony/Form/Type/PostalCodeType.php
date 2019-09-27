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


use PlanB\DDD\Domain\VO\PostalCode;
use Symfony\Component\Form\Exception\TransformationFailedException;


class PostalCodeType extends AbstractSingleType
{

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'planb.postal_code';
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
     * @throws TransformationFailedException when the transformation fails
     */
    public function reverseTransform($value)
    {
        return $this->resolve($value, function ($value) {
            return PostalCode::make((string)$value);
        });
    }


    protected function getRequiredErrorMessage(): string
    {
        return 'El código postal es requerido';
    }
}
