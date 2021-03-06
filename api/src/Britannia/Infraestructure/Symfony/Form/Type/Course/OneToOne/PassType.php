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
use Britannia\Domain\Entity\Course\Pass\PassDto;
use Britannia\Domain\Entity\Lesson\LessonList;
use Britannia\Domain\VO\Course\Pass\PassHours;
use Britannia\Domain\VO\Course\Pass\Validator\Pass as PassConstraint;
use Britannia\Infraestructure\Symfony\Form\Type\Course\PassHoursType;
use Britannia\Infraestructure\Symfony\Form\Type\Date\DateType;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('hours', PassHoursType::class, [
            'label' => 'Num. Horas'
        ]);

        $today = CarbonImmutable::tomorrow();

        $builder->add('start', DateType::class, [
            'label' => 'Valido desde',
        ]);

        $builder->add('end', DateType::class, [
            'label' => 'Valido hasta',
            'disabled' => true
        ]);

        $builder->add('lessons', CollectionType::class, [
            'label' => 'Lecciones',
            'required' => false,
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'error_bubbling' => false,
            'entry_type' => PassLessonType::class,
            'entry_options' => []


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

    public function customMapping(array $data, ?Pass $pass = null)
    {
        $lessons = array_filter($data['lessons']);
        $dto = PassDto::fromArray([
            'lessonList' => LessonList::collect($lessons),
            'course' => $this->getOption('course'),
            'hours' => $data['hours'],
            'start' => CarbonImmutable::make($data['start']),
        ]);

        if ($pass instanceof Pass) {
            return $pass->update($dto);
        }

        return Pass::make($dto);
    }
}
