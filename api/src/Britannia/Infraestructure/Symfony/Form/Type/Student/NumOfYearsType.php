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

namespace Britannia\Infraestructure\Symfony\Form\Type\Student;


use Britannia\Domain\VO\Student\OtherAcademy\NumOfYears;
use Britannia\Domain\VO\Validator;
use PlanB\DDDBundle\Symfony\Form\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumOfYearsType extends EnumType
{
    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false
        ]);
    }

    public function getEnumClass(): string
    {
        return NumOfYears::class;
    }
}
