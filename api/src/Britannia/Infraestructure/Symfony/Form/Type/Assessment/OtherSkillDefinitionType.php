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


use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\Skill;
use Britannia\Infraestructure\Symfony\Admin\Mark\MarkAdmin;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OtherSkillDefinitionType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CourseTerm $courseTerm */
        $courseTerm = $options['data'];

        $addUrl = null;
        $removeUrl = null;
        $uniqId = null;

        if ($options['admin'] instanceof MarkAdmin) {
            $addUrl = $options['admin']->generateUrl('add-skill');
            $removeUrl = $options['admin']->generateUrl('remove-skill');
            $uniqId = $options['admin']->getUniqid();
        }

        $builder
            ->add('courseId', HiddenType::class, [
                'data' => $courseTerm->courseId()

            ])
            ->add('uniqId', HiddenType::class, [
                'data' => $uniqId
            ])
            ->add('termName', HiddenType::class, [
                'data' => $courseTerm->termName()

            ])->add('skill', HiddenType::class, [
                'data' => $options['skill']

            ])->add('date', DatePickerType::class, [
                'label' => 'Fecha',
                'data' => CarbonImmutable::today()

            ])->add('add', ButtonType::class, [
                'label' => 'Nuevo examen',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'value' => $addUrl
                ]

            ])->add('delete', ButtonType::class, [
                'label' => 'Borrar examen',
                'attr' => [
                    'class' => 'btn btn-link delete',
                    'value' => $removeUrl
                ]
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CourseTerm::class,
        ]);

        $resolver->setRequired('skill', [Skill::class]);
        $resolver->setRequired('admin', [MarkAdmin::class]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['admin'] = $options['admin'];

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
