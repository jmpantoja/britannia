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

namespace Britannia\Domain\Entity\Book;


use Britannia\Domain\VO\Course\Book\BookCategory;
use Carbon\CarbonImmutable;
use Exception;
use PlanB\DDD\Domain\VO\Price;

class Book
{
    /**
     * @var TermId
     */
    private $id;

    /**
     * @var null|string
     */
    private $name;

    /**
     * @var BookCategory
     */
    private $category;

    /**
     * @var Price
     */
    private $pvp;

    /**
     * @var Price
     */
    private $price;

    /**
     * @var CarbonImmutable
     */
    private $createdAt;

    /**
     * @var CarbonImmutable
     */
    private $updatedAt;


    public static function make(BookDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(BookDto $dto)
    {
        $this->id = new BookId();
        $this->update($dto);

        $this->createdAt = CarbonImmutable::now();
    }

    public function update(BookDto $dto): self
    {
        $this->name = $dto->name;
        $this->pvp = $dto->pvp;
        $this->price = $dto->price;
        $this->category = $dto->category;

        $this->updatedAt = CarbonImmutable::now();
        return $this;
    }

    /**
     * @return BookId
     */
    public function id(): ?BookId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name ?? (string)$this->id();
    }

    /**
     * @return Price
     */
    public function pvp(): Price
    {
        return $this->pvp;
    }

    /**
     * @return BookCategory
     */
    public function category(): BookCategory
    {
        return $this->category;
    }

    public function __toString()
    {
        return $this->name();
    }


}
