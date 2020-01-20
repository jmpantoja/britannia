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
use Britannia\Domain\VO\Assessment\TermDefinition;
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
        /** @var TermList $termList */
        $termList = $options['data'];

        $builder->add('definition', TermDefinitionType::class, [
            'data' => $termList,
            'admin' => $options['admin'],
            'label' => false
        ]);

        foreach ($termList as $term) {
            $field = (string)$term->id();
//
//            $builder->add('xxx', MarkType::class, [
//                'mapped' => false,
//            ]);
//
            $builder->add($field, TermType::class, [
                'allow_extra_fields'=>true,
                'label' => $term->student(),
                'data' => $term
            ]);
        }

    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TermList::class,
            'admin' => null,
        ]);

        $resolver->setNormalizer('admin', function (OptionsResolver $resolver) {

            if(empty($resolver['sonata_field_description'])){
                return null;
            }

            return $resolver['sonata_field_description']->getAdmin();
        });

    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {

        $termList = $options['data'];

        $view->vars['units'] = $termList->units();
        $view->vars['skills'] = $termList->skills();
        $view->vars['admin'] = $options['admin'];

        parent::finishView($view, $form, $options);
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        $defintion = $data['definition'];
        unset($data['definition']);

        return TermList::collect($data)->updateDefintion($defintion);
    }
}
