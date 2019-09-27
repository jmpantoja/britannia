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


use PlanB\DDD\Domain\VO\PostalAddress;
use PlanB\DDD\Domain\VO\PostalCode;
use PlanB\DDDBundle\Symfony\Form\FormMapper;
use PlanB\DDDBundle\Symfony\Form\ReflectionDoubleMaker;
use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Validator;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PostalAddressType extends AbstractCompoundType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('address', TextType::class)
            ->add('postalCode', PostalCodeType::class, [
                'required' => $this->isRequired(),
                'error_bubbling' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'compound' => true,
            'error_bubbling' => false,
            'data_class' => PostalAddress::class,
            'empty_data' => null,
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'planb.postal_address';
    }

    /**
     * @param $data
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function mapDataToForms($data, $forms): void
    {

        if (!($data instanceof PostalAddress)) {
            return;
        }

        $this->dataToForms($data, $forms);
    }

    /**
     *
     * @param FormInterface[]|\Traversable $forms A list of {@link FormInterface} instances
     * @param $data
     * @throws \ReflectionException
     */
    public function mapFormsToData($forms, &$data): void
    {

        $mapper = $this->mapper($forms, [
            'address' => '',
            'postalCode' => '',
        ]);

        $data = $mapper
            ->cast('postalCode', PostalCode::class)
            ->resolve(function (array $values) {
                return PostalAddress::make(...[
                    $values['address'],
                    $values['postalCode']
                ]);
            });
    }

    protected function getRequiredErrorMessage(): string
    {
        return 'La dirección completa es requerida';
    }
}
