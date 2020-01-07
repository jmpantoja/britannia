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


use Britannia\Domain\VO\Mark\TermDefinition;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PercentageType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermDefinitionType extends AbstractCompoundType
{

    /**
     * @var Percent
     */
    private $unitPercentWeight;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->unitPercentWeight = $this->getUnitPercentWeight($parameterBag);
    }

    /**
     * @param ParameterBagInterface $parameterBag
     * @return Percent
     */
    private function getUnitPercentWeight(ParameterBagInterface $parameterBag): Percent
    {
        $marks = $parameterBag->get('marks') ?? [];
        $unitPercentWeight = $marks['unit_percent_weight'] ?? 30;

        return Percent::make($unitPercentWeight);
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('numOfUnits', NumOfUnitsType::class, [
                'required' => true,
            ])
            ->add('unitsWeight', PercentageType::class, [
                'label' => '% unidades',
                'empty_data' => $this->unitPercentWeight,
            ])
            ->add('examWeight', PercentageType::class, [
                'disabled' => true,
                'required' => false,
                'label' => '% examen'
            ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        /** @var TermDefinition $data */
        $data = $options['data'];

        $completedUnits = $data->completedUnits()->getValue();
        if ($completedUnits > 0) {
            $view->vars['completedUnits'] = $completedUnits;
        }
        $view->vars['term'] = $data->termName();

        parent::finishView($view, $form, $options);
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TermDefinition::class,
        ]);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return TermDefinition::buildConstraint($options);
    }

    public function customMapping(array $data)
    {
        /** @var TermDefinition $original */
        $original = $this->getOption('data');

        return TermDefinition::make(...[
            $original->termName(),
            $data['unitsWeight'],
            $data['numOfUnits'],
            $original->completedUnits()
        ]);
    }

}
