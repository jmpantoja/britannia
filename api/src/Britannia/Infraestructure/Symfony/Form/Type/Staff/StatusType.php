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

namespace Britannia\Infraestructure\Symfony\Form\Type\Staff;


use Britannia\Domain\VO\StaffMember\Status;
use Britannia\Domain\VO\Validator;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDDBundle\Symfony\Form\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends EnumType
{
    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => true,
            'attr' => [
                'style' => 'width:170px'
            ]
        ]);
    }

    public function getEnumClass(): string
    {
        return Status::class;
    }
}
