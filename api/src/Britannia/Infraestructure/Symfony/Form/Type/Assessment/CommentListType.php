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
use Britannia\Domain\VO\Assessment\CourseTerm;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentListType extends AbstractCompoundType
{
    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CourseTerm $courseTerm */
        $courseTerm = $options['data'];
        $termList = $courseTerm->termList();

        foreach ($termList as $term) {
            $field = (string)$term->id();
            $builder->add($field, CommentType::class, [
                'label' => $term->student(),
                'data' => $term
            ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CourseTerm::class,
        ]);
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
