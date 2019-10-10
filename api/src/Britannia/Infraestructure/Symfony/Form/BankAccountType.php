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

namespace Britannia\Infraestructure\Symfony\Form;


use Britannia\Domain\VO\BankAccount;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\FormDataMapper;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\CityAddressType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\IbanType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class BankAccountType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('fullName', FullNameType::class, [
                'label' => false,
                'required' => false
            ])
            ->add('iban', IbanType::class, [
                'required' => false
            ])
            ->add('number', TextType::class, [
                'required' => false,
            ])
            ->add('cityAddress', CityAddressType::class, [
                'label' => false,
                'required' => false
            ]);


    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BankAccount::class,
            'error_bubbling' => false,
            'required' => false,
            'required_error' => 'Se necesita una cuenta bancaria completa',
        ]);
    }
//
//    protected function dataToForms($data, $forms): void
//    {
//        $forms = iterator_to_array($forms);
//
//        foreach ($forms as $name => $form) {
//
//            $method = sprintf('get%s', ucfirst($name));
//
//            $value = null;
//            if (is_callable([$data, $method])) {
//                $value = $data->{$method}();
//            }
//
//            $form->setData($value);
//        }
//    }
//
//
//    public function customMapping(FormDataMapper $mapper)
//    {
//
//        $mapper
//            ->try(function (array $data) {
//
//                return BankAccount::make(...[
//                    $data['fullName'],
//                    $data['cityAddress'],
//                    $data['iban'],
//                    (int)$data['number']
//                ]);
//            });
//
//    }
    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        return BankAccount::make(...[
            $data['fullName'],
            $data['cityAddress'],
            $data['iban'],
            (int)$data['number']
        ]);
    }
}


