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


use Britannia\Domain\Entity\Assessment\SkillMark;
use Britannia\Domain\Entity\Assessment\SkillMarkList;
use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\VO\Assessment\Skill;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SkillMarkListType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Term $term */
        $term = $options['data'];

        $skillList = $term->skillsByType($options['skill']);

        foreach ($skillList as $skillMark) {
            /** @var CarbonImmutable $date */
            $date = $skillMark->date();
            $name = $date->toDateString();

            $builder->add($name, MarkType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false,
                'data' => $skillMark->mark(),
                'error_bubbling' => true
            ]);
        }

        $builder->add('total', MarkType::class, [
            'mapped' => false,
            'label' => false,
            'required' => false,
            'disabled' => true,
            'data' => $skillList->average()
        ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Term::class,
        ]);

        $resolver->setRequired('skill');
        $resolver->setAllowedTypes('skill', [Skill::class]);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {

        /** @var Term $term */
        $term = $this->getOption('data');
        $skill = $this->getOption('skill');

        unset($data['total']);

        $input = [];
        $skill = Skill::byName((string)$skill);

        foreach ($data as $key => $mark) {
            $date = CarbonImmutable::make($key);
            $input[] = SkillMark::make($term, $skill, $mark, $date);
        }

        $skillList = SkillMarkList::collect($input);
        $term->updateExtraMarks($skill, $skillList);

        return $term;
    }

}
