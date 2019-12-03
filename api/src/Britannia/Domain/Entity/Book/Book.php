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
use PlanB\DDD\Domain\VO\Price;

class Book
{
    /**
     * @var BookId
     */
    private $id;

    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null|BookCategory
     */
    private $category;

    /**
     * @var null|Price
     */
    private $price;


    private $courses;

    public function __construct()
    {
        $this->id = new BookId();
    }


    /**
     * @return BookId
     */
    public function getId(): BookId
    {
        return $this->id;
    }


    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return Book
     */
    public function setName(?string $name): Book
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return BookCategory|null
     */
    public function getCategory(): ?BookCategory
    {
        return $this->category;
    }

    /**
     * @param BookCategory|null $category
     * @return Book
     */
    public function setCategory(?BookCategory $category): Book
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return null|Price
     */
    public function getPrice(): ?Price
    {
        return $this->price;
    }

    /**
     * @param null|Price $price
     * @return Book
     */
    public function setPrice(?Price $price): Book
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * @param mixed $courses
     * @return Book
     */
    public function setCourses($courses)
    {
        $this->courses = $courses;
        return $this;
    }


    public function __toString()
    {

        $name = (string)$this->getName();

        if (is_null($this->getPrice())) {
            return $name;
        }

        return sprintf('%s (%s €)', ...[
            $name,
            (string)$this->getPrice()
        ]);
    }

}
