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

namespace Britannia\Infraestructure\Symfony\Form\Type\Assessment;


use Britannia\Domain\VO\Assessment\SetOfSkills;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDDBundle\Symfony\Form\Type\EnumType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetOfSkillsType extends EnumType
{

    public function customOptions(OptionsResolver $resolver)

    {
        $resolver->setDefaults([
            'required' => true,
            'label' => 'Habilidades',
            'attr' => [
                'style' => 'width:450px'
            ]
        ]);
    }


    public function getEnumClass(): string
    {
        return SetOfSkills::class;
    }
}
