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

namespace Britannia\Domain\Service\Payment\Discount;


use Britannia\Domain\Entity\Book\Book;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Service\Payment\Concept;
use Britannia\Domain\VO\Discount\StudentDiscount;
use PlanB\DDD\Domain\VO\Price;

class MaterialDiscount
{
    public function calcule(Course $course, StudentDiscount $discount)
    {
        $price = $this->getTotalPrice($course);

        return Concept::normal($price);
    }

    /**
     * @param Course $course
     * @return mixed
     */
    private function getTotalPrice(Course $course)
    {
        $books = collect($course->books());

        $price = Price::make(0);

        return $books->reduce(function (Price $total, Book $book) {
            $bookPrice = $book->price();

            return $total->add($bookPrice);
        }, $price);

    }
}
