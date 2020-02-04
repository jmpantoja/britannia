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


use Britannia\Domain\VO\Assessment\Skill;
use Britannia\Domain\VO\Assessment\SkillList;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExtraSkillListType extends AbstractSingleType
{
    public function getParent()
    {
        return ChoiceType::class;
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => false,
            'choice_loader' => new CallbackChoiceLoader(function () {
                return array_flip(Skill::getConstants());
            }),
            'required' => true,
            'expanded' => true,
            'multiple' => true,
            'label' => 'Otras habilidades',
            'attr' => [
                'style' => 'width:450px'
            ]
        ]);


    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        $input = collect($data)
            ->map(fn(string $skill) => Skill::byName($skill))
            ->toArray();

        return SkillList::collect($input);
    }
}
