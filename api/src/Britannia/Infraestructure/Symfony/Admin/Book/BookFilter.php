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


use Britannia\Domain\VO\Course\Book\BookCategory;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class BookFilter extends AdminFilter
{

    public function configure()
    {
        $this->add('name', null, [
            'label' => 'Libro',
            'show_filter' => true
        ]);

        $this->add('category', 'doctrine_orm_string', [
            'show_filter' => true,
            'advanced_filter' => false,
            'field_type' => ChoiceType::class,
            'label' => 'Categoría',
            'field_options' => [
                'label' => 'Categoria',
                'choices' => array_flip(BookCategory::getConstants()),
                'placeholder' => 'Todos'
            ],
        ]);

        $this->add('pvp', null, [
            'label' => 'PVP',
        ]);
    }
}
