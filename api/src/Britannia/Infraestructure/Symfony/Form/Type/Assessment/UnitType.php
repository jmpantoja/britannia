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


use Britannia\Domain\Entity\Assessment\Unit;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Infraestructure\Symfony\Form\Type\Unit\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnitType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Unit $unit */
        $unit = $options['data'];
        $skills = $unit->term()->setOfSkills();
        $markReport = $unit->marks();

        foreach ($skills as $skill) {


            $builder->add($skill, MarkType::class, [
                'label' => false,
                'required' => false,
                'data' => $markReport->get($skill),
                'error_bubbling' => true,
                //    'data' => Mark::make(mt_rand(40, 100)/10)
            ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Unit::class,
            'error_bubbling' => true
        ]);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        /** @var Unit $unit */
        $unit = $this->getOption('data');
        //return $unit;
        $marks = MarkReport::make($data);
        $unit->updateMarks($marks);

        return $unit;
    }
}
