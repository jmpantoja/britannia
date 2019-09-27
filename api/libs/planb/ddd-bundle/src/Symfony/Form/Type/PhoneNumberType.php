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


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use PlanB\DDD\Domain\VO;


class PhoneNumberType extends AbstractType implements DataTransformerInterface
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        if ('' === $options['empty_data']) {
            $builder->addViewTransformer($this);
        }

        $builder->addModelTransformer($this);

    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'planb.phone_number';
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
        if (is_null($value)) {
            return $value;
        }

        try {
            return VO\PhoneNumber::make((string)$value);
        } catch (\Exception $exception) {
            $failure = new TransformationFailedException($exception->getMessage());
            $failure->setInvalidMessage('Número de teléfono incorrecto (ej: 999 99 99 99)');

            throw $failure;
        }

    }
}
