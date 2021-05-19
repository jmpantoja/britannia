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

namespace Britannia\Infraestructure\Symfony\Form\Type\Invoice;


use Britannia\Domain\VO\Validator;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $today = CarbonImmutable::today();

        $builder->add('month', ChoiceType::class, [
            'label' => 'Mes',
            'required' => false,
            'data' => $today->month,
            'choices' => [
                'enero' => 1,
                'febrero' => 2,
                'marzo' => 3,
                'abril' => 4,
                'mayo' => 5,
                'junio' => 6,
                'julio' => 7,
                'agosto' => 8,
                'septiembre' => 9,
                'octubre' => 10,
                'noviembre' => 11,
                'diciembre' => 12,
            ],
        ]);

        $builder->add('year', ChoiceType::class, [
            'label' => 'Año',
            'required' => false,
            'data' => $today->year,
            'choices' => $this->getYears($today)
        ]);

        $builder->add('submit', SubmitType::class, [
            'label' => 'Generar Xlsx',
            'attr' => [
                'class' => 'btn btn-success'
            ]
        ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return $data;
    }

    /**
     * @return array
     */
    public function getYears(CarbonImmutable $today): array
    {
        $years = [];
        $current = $today->year;

        for ($year = 2006; $year <= $current + 2; $year++) {
            $years[$year] = $year;
        }
        return $years;
    }


}
