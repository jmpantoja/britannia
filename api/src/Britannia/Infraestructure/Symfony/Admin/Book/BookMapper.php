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
use Britannia\Domain\Entity\Book\BookDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class BookMapper extends AdminMapper
{
    public function __construct()
    {
        parent::__construct(Book::class);
    }

    protected function className(): string
    {
        return Book::class;
    }

    protected function create(array $values): Book
    {
        $dto = BookDto::fromArray($values);

        return Book::make($dto);
    }

    /**
     * @param Book $book
     * @param array $values
     * @return Book
     */
    protected function update($book, array $values): Book
    {
        $dto = BookDto::fromArray($values);

        return $book->update($dto);
    }


}
