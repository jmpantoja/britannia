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


use Britannia\Domain\VO\Course\Assessment\Assessment;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\ToggleType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AssessmentType extends AbstractCompoundType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['is_adult'] = $options['is_adult'];
        $view->vars['is_school'] = $options['is_school'];

        parent::buildView($view, $form, $options);
    }
    
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Assessment $assessment */
        $assessment = $options['data'];

        $builder->add('numOfTerms', NumberType::class, [
            'html5' => true,
            'mapped' => false,
            'data' => $assessment->numOfTerms(),
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
            'data' => $assessment->hasDiagnosticTest()
        ]);

        $builder->add('final', ToggleType::class, [
            'on_text' => 'Si',
            'off_text' => 'No',
            'off_style' => 'info',
            'data' => $assessment->hasFinalTest()
        ]);

        $builder
            ->add('skills', SetOfSkillsType::class, [
                'label' => false,
                'required' => true,
                'data' => $assessment->skills()
            ]);


        $builder
            ->add('extraSkills', ExtraSkillListType::class, [
                'mapped' => false,
                'label' => false,
                'data' => $assessment->otherSkills()->toNamesList()
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Assessment::class,
            'is_school' => false,
            'is_adult' => false,
        ]);

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
        return Assessment::make(...[
            $data['skills'],
            $data['extraSkills'],
            (int)$data['numOfTerms'],
            $data['diagnostic'],
            $data['final']
        ]);
    }
}
