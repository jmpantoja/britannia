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

namespace Britannia\Infraestructure\Symfony\Form\Report\CourseMarks;


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\CourseInfoData;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChooseStudentType extends AbstractSingleType
{

    public function getParent()
    {
        return HiddenType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'student'
        ]);
        $resolver->addAllowedTypes('student', [Student::class]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['student'] = $options['student'];
        return parent::finishView($view, $form, $options);
    }


    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return (bool)$data;

    }
}
