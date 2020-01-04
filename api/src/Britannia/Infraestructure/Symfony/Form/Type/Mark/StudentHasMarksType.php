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


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Mark\SetOfSkills;
use Britannia\Infraestructure\Symfony\Form\Type\Unit\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentHasMarksType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $units = $options['units'];
        $student = $options['student'];

        foreach ($units as $unit) {
            $name = (string)$unit->id();

            $data = $unit->marksByStudent($student);

            $builder->add($name, UnitHasMarksType::class, [
                'label' => false,
                'required' => false,
                'data' => $data,
                'is_total' => $unit->isTotal()
            ]);
        }
    }

    protected function dataToForms($data, array $forms): void
    {
        parent::dataToForms($data, $forms);
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('student');
        $resolver->setAllowedTypes('student', [Student::class]);

        $resolver->setRequired('units');
        $resolver->setAllowedTypes('units', 'array');
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['student'] = $options['student'];
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
