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


use Britannia\Domain\Entity\Book\Book;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class BookDataSource extends AdminDataSource
{
    public function __invoke(Book $book)
    {
        $data['Nombre'] = $this->parse($book->name());
        $data['Tipo'] = $this->parse($book->category());
        $data['Precio'] = $this->parse($book->pvp());

        return $data;
    }
}
