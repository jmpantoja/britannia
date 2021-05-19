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


use Britannia\Domain\Entity\Assessment\TermList;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Infraestructure\Symfony\Form\Type\Unit\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinalExamListType extends AbstractCompoundType
{
    public function getBlockPrefix()
    {
        return 'single_exam';
    }


    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Course $course */
        $course = $options['data'];

        $courseHasStudents = $course->activeCourseHasStudents();

        foreach ($courseHasStudents as $courseHasStudent) {
            $field = $this->getFieldName($courseHasStudent);

            $builder->add($field, FinalExamType::class, [
                'label' => $courseHasStudent->student(),
                'data' => $courseHasStudent
            ]);
        }
    }

    /**
     * @param StudentCourse $courseHasStudent
     * @return string
     */
    private function getFieldName(StudentCourse $courseHasStudent): string
    {
        return sprintf('%s-%s', ...[
            $courseHasStudent->course()->id(),
            $courseHasStudent->student()->id(),
        ]);
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        /** @var Course $course */
        $course = $options['data'];
        $view->vars['skills'] = $course->skills();

        parent::finishView($view, $form, $options);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        return $this->getOption('data');
    }


}
