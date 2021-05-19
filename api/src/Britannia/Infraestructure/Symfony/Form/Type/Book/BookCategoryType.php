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


use Britannia\Domain\VO\Course\Book\BookCategory;
use Britannia\Domain\VO\Validator;
use PlanB\DDDBundle\Symfony\Form\Type\EnumType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookCategoryType extends EnumType
{


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => true,
            'attr' => array('style' => 'max-width: 150px')
        ]);
    }


    public function getEnumClass(): string
    {
        return BookCategory::class;
    }
}
