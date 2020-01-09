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
use Britannia\Domain\VO\Assessment\Mark;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Infraestructure\Symfony\Form\Type\Unit\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExamType extends AbstractCompoundType
{

    public function getBlockPrefix(){
        return 'unit';
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Term $term */
        $term = $options['data'];
        $skills = $term->skills();
        $markReport = $term->exam();

        foreach ($skills as $skill) {

            $builder->add($skill, MarkType::class, [
                'label' => false,
                'required' => false,
                'data' => $markReport->get($skill),
//                'data' => Mark::make(mt_rand(40, 100)/10)
            ]);
        }
    }

    protected function dataToForms($data, array $forms): void
    {
        parent::dataToForms($data, $forms);
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
        return MarkReport::make($data);
    }
}
