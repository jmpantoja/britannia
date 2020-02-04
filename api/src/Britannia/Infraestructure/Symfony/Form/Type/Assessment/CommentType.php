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
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Term $term */
        $term = $options['data'];

        $builder->add('comment', TextareaType::class, [
            'mapped' => false,
            'label' => false,
            'required' => false,
            'data' => $term->comment(),
            'attr' => [
                'maxlength' => 430
            ]
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

    public function customMapping($data)
    {
        /** @var Term $term */
        $term = $this->getOption('data');
        $term->updateComment($data['comment']);

        return $term;
    }

}
