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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course\Discount;


use Britannia\Domain\VO\Discount\FamilyDiscountList;
use Britannia\Domain\VO\Discount\FamilyOrder;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PercentageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamilyDiscountListType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        //El hermano de mayor precio siempre paga la mensualidad completa
        $familyOrderList = [
            FamilyOrder::LOWER(),
            FamilyOrder::DEFAULT(),
        ];

        $upper = FamilyOrder::UPPER();
        $lower = FamilyOrder::LOWER();
        $default = FamilyOrder::DEFAULT();

        $builder->add($upper->getName(), PercentageType::class, [
            'required' => false,
            'label' => $upper->getValue(),
            'disabled' => true
        ]);

        $builder->add($lower->getName(), PercentageType::class, [
            'required' => false,
            'label' => $lower->getValue(),
        ]);

        $builder->add($default->getName(), PercentageType::class, [
            'required' => false,
            'label' => $default->getValue(),
        ]);

    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FamilyDiscountList::class
        ]);

    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }


    public function customMapping(array $data)
    {
        $upper = (string)FamilyOrder::UPPER();
        $lower = (string)FamilyOrder::LOWER();
        $default = (string)FamilyOrder::DEFAULT();


        return FamilyDiscountList::make(...[
            $data[$upper] ?? Percent::zero(),
            $data[$lower] ?? Percent::zero(),
            $data[$default] ?? Percent::zero(),
        ]);
    }
}
