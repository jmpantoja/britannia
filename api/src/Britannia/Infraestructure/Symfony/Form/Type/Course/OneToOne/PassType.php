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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course\OneToOne;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Pass\Pass;
use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\VO\Course\Pass\PassHours;
use Britannia\Domain\VO\Course\Pass\Validator\Pass as PassConstraint;
use Britannia\Infraestructure\Symfony\Form\Type\Course\PassHoursType;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('id', TextType::class);

        $builder->add('hours', PassHoursType::class, [
            'label' => 'Num. Horas'
        ]);

        $today = CarbonImmutable::tomorrow();
        $defaultStart = date_to_string($today, \IntlDateFormatter::MEDIUM);
        $defaultEnd = date_to_string($today->lastOfMonth(), \IntlDateFormatter::MEDIUM);

        $builder->add('start', DatePickerType::class, [
            'label' => 'Valido desde',
            'dp_default_date' => $defaultStart
        ]);

        $builder->add('end', DatePickerType::class, [
            'label' => 'Valido hasta',
            'dp_default_date' => $defaultEnd,
            'attr' => [
                'readonly' => true
            ]
        ]);

        $builder->add('lessons', CollectionType::class, [
            'label' => 'Lecciones',
            'required' => false,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'error_bubbling' => false,
            'entry_type' => PassLessonType::class
        ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('course');
        $resolver->setAllowedTypes('course', [Course::class]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        $hours = $view['hours']->vars['data'];

        $view->vars['numOfMinutes'] = 0;

        if ($hours instanceof PassHours) {
            $view->vars['numOfMinutes'] = $hours->toMinutes();
        }

        parent::finishView($view, $form, $options);
    }


    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return new PassConstraint();
    }

    public function customMapping(array $data)
    {
        $lessonList = LessonList::collect($data['lessons']);

        $course = $this->getOption('course');
        $hours = $data['hours'];
        $start = CarbonImmutable::make($data['start']);

        return Pass::make($course, $hours, $start, $lessonList);
    }
}
