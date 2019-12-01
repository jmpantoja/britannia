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

namespace Britannia\Infraestructure\Symfony\Form\Type\Book;


use Britannia\Domain\VO\BookCategory;
use Britannia\Domain\VO\Validator;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookCategoryType extends AbstractSingleType
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'choice_loader' => new CallbackChoiceLoader(function () {
                $values = array_flip(BookCategory::getConstants());
                return array_merge(['' => ''], $values);
            }),
            'attr' => array('style' => 'max-width: 150px')
        ]);
    }

    /**
     * @param array $options
     * @return null|Constraint
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return new Validator\BookCategory([
            'required' => $options['required']
        ]);
    }

    public function customMapping($data)
    {
        return BookCategory::byName($data);
    }
}
