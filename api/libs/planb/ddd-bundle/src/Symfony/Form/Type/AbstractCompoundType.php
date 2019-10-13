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
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validation;

abstract class AbstractCompoundType extends AbstractType implements DataMapperInterface
{

    private $options;

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

    final public function getBlockPrefix()
    {
        return parent::getBlockPrefix();
    }


    /**
     * @param $forms
     * @return array
     */
    protected function getOptions($forms): array
    {
        $parent = $this->getParentForm($forms);
        $options = $parent->getConfig()->getOptions();
        return $options;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view->children as $child) {
            $child->isChild = true;
        }
    }


    final public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setDataMapper($this);
        $this->customForm($builder, $options);

    }

    abstract public function customForm(FormBuilderInterface $builder, array $options);

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
            'compound' => true,
            'empty_data' => null,
            'error_bubbling' => false,
            'required' => true
        ]);

        $this->customOptions($resolver);
    }


    abstract public function customOptions(OptionsResolver $resolver);

    /**
     * @param mixed $data
     * @param FormInterface[]|\Traversable $forms
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    final public function mapDataToForms($data, $forms): void
    {
        $forms = iterator_to_array($forms);

        $options = $this->getOptions($forms);
        $className = $options['data_class'];

        if (!($data instanceof $className)) {
            return;
        }

        $this->dataToForms($data, $forms);

    }

    /**
     * @param $data
     * @param $forms
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    protected function dataToForms($data, array $forms): void
    {
        foreach ($forms as $name => $form) {
            $method = sprintf('get%s', ucfirst($name));

            $value = null;
            if (is_callable([$data, $method])) {
                $value = $data->{$method}();
            }

            $form->setData($value);
        }
    }


    /**
     * @param FormInterface[]|\Traversable $forms
     * @param $data
     */
    final public function mapFormsToData($forms, &$data): void
    {

        $forms = iterator_to_array($forms);

        $values = array_map(function ($form) {
            return $form->getData();
        }, $forms);

        $options = $this->getOptions($forms);

        $constraint = $this->buildConstraint($options);

        if (!($constraint instanceof Constraint)) {
            $data = $this->customMapping($values);
            return;
        }

        if ($constraint->isEmptyAndOptional($values)) {
            $data = null;
            return;
        }

        if ($this->validate($values, $forms, $constraint)) {
            $data = $this->customMapping($values);
            return;
        }
    }


    /**
     * @param $data
     * @param Constraint $constraint
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected function validate($data, array $forms, Constraint $constraint): bool
    {
        $validator = Validation::createValidator();
        $violationList = $validator->validate($data, $constraint);

        if (count($violationList) === 0) {
            return true;
        }

        foreach ($violationList as $name => $violation) {

            $form = $this->getFormTarget($forms, $violation);

            if ($form instanceof FormInterface) {
                $form->addError(new FormError($violation->getMessage()));
            }
        }

        return false;
    }

    private function getFormTarget(array $forms, ConstraintViolationInterface $violation): ?FormInterface
    {
        $path = $this->getViolationPath($violation);

        if (!isset($forms[$path])) {
            return $this->getParentForm($forms);
        }

        if ($this->formWithErrors($forms[$path])) {
            return null;
        }

        return $forms[$path];
    }

    private function formWithErrors(FormInterface $form): bool
    {
        $withTransformationFailure = $form->getTransformationFailure() !== null;

        $withErrors = count($form->getErrors()) > 0;

        return $withTransformationFailure || $withErrors;
    }

    /**
     * @param array $forms
     * @return array
     */
    protected function getParentForm(array $forms): ?FormInterface
    {

        $parent = null;
        $form = reset($forms);
        if ($form) {
            $parent = $form->getParent();
        }

        return $parent;
    }

    /**
     * @param ConstraintViolationInterface $violation
     * @return mixed
     */
    private function getViolationPath(ConstraintViolationInterface $violation): string
    {
        $violationPath = $violation->getPropertyPath();
        return str_replace(['[', ']'], '', $violationPath);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    abstract public function buildConstraint(array $options): ?Constraint;

    abstract public function customMapping(array $data);

}
