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


use PlanB\DDDBundle\Symfony\Form\Type\ToggleType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FreeEnrollmentType extends ToggleType
{

    public function getBlockPrefix()
    {
        return 'toggle';
    }

    public function customOptions(OptionsResolver $resolver)
    {
        parent::customOptions($resolver);

        $resolver->setDefaults([
            'label' => '¿matricula gratis?',
            'empty_data' => false,
            'required' => false,
            'width' => 170,

            'off_text' => 'NO, matricula de pago',
            'off_style' => 'info',
            'on_text' => 'SI, matricula gratis',
            'on_style' => 'success',
        ]);


    }
}
