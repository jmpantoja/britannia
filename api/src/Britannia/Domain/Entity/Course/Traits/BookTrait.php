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

namespace Britannia\Domain\Entity\Course\Traits;


use Britannia\Domain\Entity\Book\Book;
use Britannia\Domain\Entity\Book\BookList;
use Britannia\Domain\Entity\Course\CourseDto;
use Doctrine\Common\Collections\Collection;

trait BookTrait
{
    /**
     * @var Collection
     */
    private $books;


    /**
     * @param CourseDto $dto
     */
    private function updateBooks(CourseDto $dto): self
    {
        $books = BookList::collect($dto->books);

        $this->bookList()
            ->forRemovedItems($books, [$this, 'removeBook'])
            ->forAddedItems($books, [$this, 'addBook']);

        return $this;
    }

    public function removeBook(Book $book): self
    {
        $this->bookList()->remove($book);
        return $this;
    }

    public function addBook(Book $book): self
    {
        $this->bookList()->add($book);
        return $this;
    }

    /**
     * @return Collection
     */
    public function books(): array
    {
        return $this->bookList()
            ->toArray();
    }

    protected function bookList(): BookList
    {
        return BookList::collect($this->books);
    }
}
