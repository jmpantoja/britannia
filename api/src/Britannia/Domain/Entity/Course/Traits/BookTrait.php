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


use Doctrine\Common\Collections\Collection;

trait BookTrait
{
    /**
     * @var Collection
     */
    private $books;

    /**
     * @return Collection
     */
    public function books(): Collection
    {
        return $this->books;
    }

}
