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

namespace Britannia\Infraestructure\Symfony\Admin\Book;


use Britannia\Infraestructure\Symfony\Form\Type\Book\BookCategoryType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class BookForm extends AdminForm
{

    public function configure()
    {
        $this->tab('Curso');

        $this->group('Nombre', ['class' => 'col-md-4'])
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank()
                ],
                'attr' => [
                    'style' => 'width: 300px'
                ]
            ])
            ->add('category', BookCategoryType::class);

        $this->group('Precio', ['class' => 'col-md-4'])
            ->add('price', PriceType::class);
    }
}
