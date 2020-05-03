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

namespace Britannia\Infraestructure\Symfony\Form\Type\Message;


use Britannia\Domain\VO\Message\EmailPurpose;
use PlanB\DDDBundle\Symfony\Form\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class EmailPurposeType extends EnumType
{

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'placeholder' => 'Indefinido',
            'attr' => [
                'style' => 'width:235px'
            ]
        ]);
    }

    public function getEnumClass(): string
    {
        return EmailPurpose::class;
    }
}
