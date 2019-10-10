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

use PlanB\DDD\Domain\VO\Validator\Constraint;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Validation;

abstract class AbstractSingleType extends AbstractType implements DataTransformerInterface
{

    /**
     * @var FormBuilderInterface
     */
    private $builder;

    final public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->builder = $builder;
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

        if (is_null($value)) {
            return $value;
        }

        $options = $this->builder->getOptions();

        if (!class_exists((string)$options['data_class'])) {
            return (string)$value;
        }

        if (is_a($value, $options['data_class'])) {
            return $value;
        }

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
        $options = $this->builder->getOptions();

        $constraint = $this->buildConstraint($options);


        if (!($constraint instanceof Constraint)) {
            return $this->customMapping($value);
        }

        if ($constraint->isEmptyAndOptional($value)) {
            return null;
        }

        if ($this->validate($value, $constraint)) {
            return $this->customMapping($value);
        }
    }


    private function validate($data, Constraint $constraint): bool
    {
        $validator = Validation::createValidator();
        $violationList = $validator->validate($data, $constraint);

        if (count($violationList) === 0) {
            return true;
        }

        throw $this->createException($violationList);
    }

    /**
     * @param $violationList
     * @return TransformationFailedException
     */
    private function createException($violationList): TransformationFailedException
    {
        $messages = [];

        foreach ($violationList as $name => $violation) {
            $messages[] = $violation->getMessage();
        }

        $message = implode("\n", $messages);
        $failure = new TransformationFailedException((string)$violationList);
        $failure->setInvalidMessage($message);
        return $failure;
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    abstract public function buildConstraint(array $options): ?Constraint;

    abstract public function customMapping($data);

}
