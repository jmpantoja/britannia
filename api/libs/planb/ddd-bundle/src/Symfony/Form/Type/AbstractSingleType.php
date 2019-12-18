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

use Britannia\Domain\VO\Course\TimeTable\TimeSheet;
use Britannia\Infraestructure\Symfony\Form\TimeSheetListType;
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
    protected $builder;

    private $options;

    private $empty_data;

    final public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->builder = $builder;
        $this->options = $this->builder->getOptions();

        $builder->addModelTransformer($this);
        $this->customForm($builder, $options);

    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['pre_icon'] = $options['pre_icon'];
        $view->vars['post_icon'] = $options['post_icon'];

        parent::buildView($view, $form, $options);
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
     * @return mixed
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $key, $default = null)
    {
        return $this->options[$key] ?? $default;
    }


    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->isSubmitted()) {
            parent::finishView($view, $form, $options);
            return;
        }

        $data = $form->getData();
        if (is_null($data)) {

            $default = $options['empty_data'];
            $form->setData($default);
            $view->vars['data'] = $default;
            $view->vars['value'] = $default;
        }
        parent::finishView($view, $form, $options);
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
            'pre_icon' => null,
            'post_icon' => null,
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
        $options = $this->getOptions();

        $value = $value ?? $options['empty_data'] ?? null;

        if (is_null($value)) {
            return $value;
        }

        if (!class_exists((string)$options['data_class'])) {
            return $value;
        }

        if (is_a($value, $options['data_class'])) {
            return $value;
        }

        return $value;

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
        $this->options = $this->builder->getOptions();

        $constraint = $this->buildConstraint($this->options);

        if (!($constraint instanceof Constraint)) {
            return $this->customMapping($value);
        }

        if ($constraint->isEmptyAndOptional($value)) {
            return null;
        }

        if ($this->validate($value, $constraint)) {
            return $this->customMapping($value);
        }

        return null;
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
