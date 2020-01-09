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


use Britannia\Domain\Entity\Assessment\TermList;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TermListType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $termList = $options['data'];

        foreach ($termList as $term) {
            $field = (string)$term->id();

            $builder->add($field, TermType::class, [
                'label' => $term->student(),
                'data' => $term
            ]);
        }

    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TermList::class,
        ]);

    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $termList = $options['data'];

        $view->vars['units'] = $termList->units();
        $view->vars['skills'] = $termList->skills();

        parent::finishView($view, $form, $options);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {

        return TermList::collect($data);
    }
}
