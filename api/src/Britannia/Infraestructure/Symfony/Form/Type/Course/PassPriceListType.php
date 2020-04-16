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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course;


use Britannia\Domain\VO\Course\Pass\PassHours;
use Britannia\Domain\VO\Course\Pass\PassPriceList;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassPriceListType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $tenHours = PassHours::TEN_HOURS();
        $fiveHours = PassHours::FIVE_HOURS();
        $oneHour = PassHours::ONE_HOUR();

        $builder->add($tenHours->getName(), PriceType::class, [
            'required' => false,
            'label' => $tenHours->getValue(),
            'attr' => [
                'style' => 'width:120px'
            ]
        ]);

        $builder->add($fiveHours->getName(), PriceType::class, [
            'required' => false,
            'label' => $fiveHours->getValue(),
            'attr' => [
                'style' => 'width:120px'
            ]
        ]);

        $builder->add($oneHour->getName(), PriceType::class, [
            'required' => false,
            'label' => $oneHour->getValue(),
            'attr' => [
                'style' => 'width:120px'
            ]
        ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PassPriceList::class
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

        $tenHours = (string)PassHours::TEN_HOURS();
        $fiveHours = (string)PassHours::FIVE_HOURS();
        $oneHour = (string)PassHours::ONE_HOUR();

        return PassPriceList::make([
            $tenHours => $data[$tenHours] ?? null,
            $fiveHours => $data[$fiveHours] ?? null,
            $oneHour => $data[$oneHour] ?? null,
        ]);
    }
}
