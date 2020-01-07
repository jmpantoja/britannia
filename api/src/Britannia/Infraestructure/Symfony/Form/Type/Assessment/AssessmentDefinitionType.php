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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Mark\AssessmentDefinition;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PercentageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssessmentDefinitionType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Course $course */
        $course = $options['course'];

        $skills = $course->skills();
        $unitsWeight = $course->unitsWeight();


        $builder
            ->add('skills', SetOfSkillsType::class, [
                'label' => false,
                'data' => $skills
            ])
            ->add('unitsWeight', PercentageType::class, [
                'label' => '% unidades',
                'data' => $unitsWeight
            ])
            ->add('examWeight', PercentageType::class, [
                'disabled' => true,
                'required' => false,
                'label' => '% examen',
                'data' => $unitsWeight->complementary()
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AssessmentDefinition::class
        ]);

        $resolver->setRequired('course');
        $resolver->addAllowedTypes('course', [Course::class]);
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
        return AssessmentDefinition::make(...[
            $data['skills'],
            $data['unitsWeight']
        ]);
    }
}
