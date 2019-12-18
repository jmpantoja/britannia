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

namespace PlanB\DDD\Domain\Behaviour\Traits;

use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;

trait ComparableTrait
{
    public function hash()
    {
        return spl_object_hash($this);
    }

    public function compareTo(object $other): int
    {
        $this->assertThatCanBeCompared($other);
        if ($this->isAggreate()) {
            return $this->id()->compareTo($other->id());
        }

        return strcmp($this->hash(), spl_object_hash($other));
    }

    public function equals(object $other): bool
    {
        $this->assertThatCanBeCompared($other);
        if ($this->isAggreate()) {
            return $this->id()->equals($other->id());
        }

        return $this == $other;
    }

    /**
     * @param object $other
     */
    protected function assertThatCanBeCompared(object $other): void
    {
        if (!($other instanceof self)) {
            throw new \InvalidArgumentException('No se pueden comparar objetos de diferentes tipos');
        }
    }

    private function isAggreate(): bool
    {
        $traits = class_uses($this);
        if (in_array(AggregateRootTrait::class, $traits)) {
            return true;
        }
        return false;
    }
}
