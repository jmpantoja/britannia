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

namespace Britannia\Infraestructure\Symfony\Form\Type\Mark;


use Britannia\Domain\VO\Mark\NumOfUnits;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumOfUnitsType extends AbstractSingleType
{
    public function getParent()
    {
        return ChoiceType::class;
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => NumOfUnits::class,
            'expanded' => false,
            'choice_loader' => new CallbackChoiceLoader(function () {
                return array_flip(NumOfUnits::getConstants());
            }),
            'empty_data' => (string)NumOfUnits::THREE(),
            'required' => false,
            'label' => 'nº de unidades',
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return new \Britannia\Domain\VO\Mark\Validator\NumOfUnits([
            'required' => $options['required']
        ]);
    }

    public function customMapping($data)
    {

        return NumOfUnits::byName($data);
    }
}
