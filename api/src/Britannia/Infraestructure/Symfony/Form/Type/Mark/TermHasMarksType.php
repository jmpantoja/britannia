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

namespace Britannia\Infraestructure\Symfony\Form\Type\Mark;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Infraestructure\Symfony\Form\Type\Unit\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermHasMarksType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Course $course */
        $course = $options['course'];
        $students = $course->students();

        foreach ($students as $student) {
            $name = (string)$student->id();
            $builder->add($name, StudentHasMarksType::class, [
                'label' => false,
                'student' => $student,
                'units' => $options['units']
            ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('units');
        $resolver->setAllowedTypes('units', 'array');

        $resolver->setRequired('course');
        $resolver->setAllowedTypes('course', [Course::class]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['units'] = $options['units'];
        $view->vars['skills'] = $options['course']->evaluableSkills();

        parent::finishView($view, $form, $options);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        return $data;
    }
}
