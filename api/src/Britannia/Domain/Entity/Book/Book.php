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
use PlanB\DDD\Domain\VO\Price;

final class Book
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
        $this->id = new TermId();
        $this->update($dto);

        $this->createdAt = CarbonImmutable::now();
    }

    public function update(BookDto $dto)
    {
        $this->name = $dto->name;
        $this->price = $dto->price;
        $this->category = $dto->category;

        $this->updatedAt = CarbonImmutable::now();
    }

    /**
     * @return TermId
     */
    public function id(): ?TermId
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
    public function price(): Price
    {
        return $this->price;
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
