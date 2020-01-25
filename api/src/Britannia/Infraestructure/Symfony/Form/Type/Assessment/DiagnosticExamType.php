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


use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\VO\Assessment\Mark;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Infraestructure\Symfony\Form\Type\Unit\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiagnosticExamType extends AbstractCompoundType
{

    public function getBlockPrefix()
    {
        return 'unit';
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var StudentCourse $studentCourse */
        $studentCourse = $options['data'];

        $skills = $studentCourse->course()->skills();
        $markReport = $studentCourse->diagnostic();

        foreach ($skills as $skill) {
            $builder->add($skill, MarkType::class, [
                'label' => false,
                'required' => false,
                'data' => $markReport->get($skill),
            //    'data' => Mark::make(mt_rand(40, 100)/10)
            ]);
        }

        $builder->add('total', MarkType::class, [
            'label' => false,
            'required' => false,
            'disabled' => true,
            'data' => $markReport->average($skills)
        ]);

    }

    protected function dataToForms($data, array $forms): void
    {
        parent::dataToForms($data, $forms);
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StudentCourse::class
        ]);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        /** @var StudentCourse $studentCourse */
        $studentCourse = $this->getOption('data');
        $markReport = MarkReport::make($data);

        return $studentCourse->setDiagnostic($markReport);
    }
}
