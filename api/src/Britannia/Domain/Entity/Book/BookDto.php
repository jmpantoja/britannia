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
use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\Price;

final class BookDto extends Dto
{
    public ?string $name;
    public ?Price $pvp;
    public ?Price $price;
    public ?BookCategory $category;
}
