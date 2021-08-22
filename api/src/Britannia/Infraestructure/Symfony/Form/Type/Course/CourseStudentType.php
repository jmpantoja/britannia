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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course;


use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\ToggleType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseStudentType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        /** @var StudentCourse $studentCourse */
        $studentCourse = $options['studentCourse'];

        $builder
            ->add('missed', HiddenType::class, [
                'label' => false,
                'data' => !$studentCourse->onCourse()
            ])
            ->add('singlePaid', ToggleType::class, [
                'label' => false,
                'on_text' => 'Pago único',
                'off_text' => 'Dos pagos',
                'off_style' => 'success',
                'data' => $studentCourse->isSinglePaid()
            ]);

    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        /** @var StudentCourse $student */
        $student = $options['studentCourse'];
        $view->vars['student'] = $student->student();
        $view->vars['course'] = $student->course();
        $view->vars['status'] = $student->onCourse() ? 'on' : 'off';
        $view->vars['frozen'] = $student->course()->isFinalized();
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'studentCourse'
        ]);

        $resolver->setAllowedTypes('studentCourse', StudentCourse::class);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        $hasLeavedCourse = (bool)$data['missed'];

        if ($hasLeavedCourse) {
            return null;
        }

        /** @var StudentCourse $studentCourse */
        $studentCourse = $this->getOption('studentCourse');
        if (!$studentCourse->onCourse()) {
            $studentCourse = StudentCourse::make(...[
                $studentCourse->student(),
                $studentCourse->course()
            ]);
        }

        $singlePaid = (bool)$data['singlePaid'] ?? false;
        $studentCourse->setSinglePaid($singlePaid);

        return $studentCourse;
    }

}
