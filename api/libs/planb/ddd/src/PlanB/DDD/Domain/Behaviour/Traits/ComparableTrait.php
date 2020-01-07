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

use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;

trait ComparableTrait
{
    public function hash(): string
    {
        if ($this->isAggreate()) {
            return (string)$this->id();
        }
        return spl_object_hash($this);
    }

    public function compareTo(Comparable $other): int
    {
        $this->assertThatCanBeCompared($other);
        return strcmp($this->hash(), $other->hash());
    }

    public function equals(Comparable $other): bool
    {
        $this->assertThatCanBeCompared($other);
        return 0 === $this->compareTo($other);
    }

    /**
     * @param object $other
     */
    protected function assertThatCanBeCompared(Comparable $other): void
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
