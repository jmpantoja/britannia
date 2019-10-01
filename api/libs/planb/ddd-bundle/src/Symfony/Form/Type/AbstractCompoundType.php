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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    final public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->options = $options;
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
            'required' => true,
            'required_message' => 'Se necesita un valor completo'

        ]);

        $this->customOptions($resolver);
    }


    /**
     * @param mixed $data
     * @param FormInterface[]|\Traversable $forms
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    final public function mapDataToForms($data, $forms): void
    {
        $className = $this->options['data_class'];

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
    protected function dataToForms($data, $forms): void
    {
        $forms = iterator_to_array($forms);

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
        $mapper = FormDataMapper::compound($forms)
            ->setOptions($this->options);

        $this->customMapping($mapper);
        $data = $mapper->run();

    }

    abstract public function customMapping(FormDataMapper $mapper);
}
