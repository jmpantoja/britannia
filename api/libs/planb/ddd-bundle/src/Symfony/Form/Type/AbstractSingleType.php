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

use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Exceptions\ValidationException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractSingleType extends AbstractType implements DataTransformerInterface
{
    /**
     * @var array
     */
    protected $options;

    final public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options;
        $builder->addModelTransformer($this);
        $this->customForm($builder, $options);
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

    public function getBlockPrefix()
    {
        return parent::getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    final public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'data_class',
            'error_bubbling',
            'required'
        ]);

        $resolver->setDefaults([
            'empty_data' => null,
            'error_bubbling' => false,
            'required' => true,
            'required_message' => 'El valor es requerido'

        ]);

        $this->customOptions($resolver);
    }

    abstract public function customOptions(OptionsResolver $resolver);



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
    final public function reverseTransform($value)
    {
        die('sss');
        $mapper = FormDataMapper::single($value)
            ->setOptions($this->options);


        $this->customMapping($mapper);
        return $mapper->run();
    }


    abstract public function customMapping(FormDataMapper $mapper);

}
