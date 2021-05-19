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


use Britannia\Domain\VO\Discount\FamilyOrder;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDDBundle\Symfony\Form\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FamilyOrderType extends EnumType
{
    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => false,
            'required' => false,
            'label' => 'Lugar en la familia',
        ]);
    }

    public function getEnumClass(): string
    {
        return FamilyOrder::class;
    }
}
