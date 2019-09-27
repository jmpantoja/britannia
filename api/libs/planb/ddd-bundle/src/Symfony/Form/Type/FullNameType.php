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


use phpDocumentor\Reflection\Types\Parent_;
use PlanB\DDD\Domain\VO\FullName;
use Respect\Validation\Exceptions\AllOfException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FullNameType extends AbstractCompoundType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true,
            'data_class' => FullName::class,
            'empty_data' => null,
            'error_bubbling' => false
        ]);
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
        return 'planb.full_name';
    }


    /**
     * @param mixed $data
     * @param FormInterface[]|\Traversable $forms
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function mapDataToForms($data, $forms): void
    {
        if (!($data instanceof FullName)) {
            return;
        }

        $this->dataToForms($data, $forms);

    }

    /**
     * @param FormInterface[]|\Traversable $forms
     * @param $data
     */
    public function mapFormsToData($forms, &$data): void
    {
        $mapper = $this->mapper($forms, [
            'firstName' => '',
            'lastName' => '',
        ]);

        $data = $mapper->resolve(function (array $values) {

            return FullName::make(...[
                $values['firstName'],
                $values['lastName']
            ]);
        });
    }

    protected function getRequiredErrorMessage(): string
    {
        return 'Nombre y apellidos son requeridos';
    }
}

