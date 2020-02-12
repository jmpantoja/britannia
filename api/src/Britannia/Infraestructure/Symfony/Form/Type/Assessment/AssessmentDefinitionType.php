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
use Britannia\Domain\VO\Assessment\AssessmentDefinition;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\ToggleType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssessmentDefinitionType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Course $course */
        $course = $options['course'];
        $skills = $course->skills();
        $otherSkills = $course->otherSkills();
        $numOfTerms = $course->numOfTerms();


        $builder->add('numOfTerms', NumberType::class, [
            'html5' => true,
            'mapped' => false,
            'data' => $numOfTerms,
            'attr' => [
                'max' => 3,
                'min' => 0,
                'class' => 'numOfTerms'
            ]
        ]);

        $builder->add('diagnostic', ToggleType::class, [
            'on_text' => 'Si',
            'off_text' => 'No',
            'off_style' => 'info',
            'data' => $course->hasDiagnosticTest()

        ]);

        $builder->add('final', ToggleType::class, [
            'on_text' => 'Si',
            'off_text' => 'No',
            'off_style' => 'info',
            'data' => $course->hasFinalTest()
        ]);

        $builder
            ->add('skills', SetOfSkillsType::class, [
                'label' => false,
                'required' => false,
                'data' => $skills
            ]);


        $builder
            ->add('extraSkills', ExtraSkillListType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'data' => $otherSkills->toNamesList()
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
            $data['extraSkills'],
            (int)$data['numOfTerms'],
            $data['diagnostic'],
            $data['final']
        ]);
    }
}
