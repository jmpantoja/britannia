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


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Assessment\UnitList;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Term $term */
        $term = $options['data'];

        foreach ($term->units() as $unit) {
            $key = (string)$unit->id();
            $builder->add($key, UnitType::class, [
                'mapped' => false,
                'data' => $unit
            ]);
        }

        $builder->add('exam', ExamType::class, [
            'mapped' => false,
            'data' => $term
        ]);

        $builder->add('total', TotalType::class, [
            'mapped' => false,
            'data' => $term
        ]);
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Term::class
        ]);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        /** @var Term $term */
        $term = $this->getOption('data');

        $exam = $data['exam'];
        unset($data['exam']);
        unset($data['total']);

        $unitList = UnitList::collect($data);
        $term->updateMarks($unitList, $exam);

        return $term;
    }

}
