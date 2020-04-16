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

namespace PlanB\DDD\Domain\Model;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\StudentCourse;
use Countable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use IteratorAggregate;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Model\Exception\InvalidTypeException;
use PlanB\DDD\Domain\Model\Exception\ValidationException;

abstract class EntityList implements Countable, IteratorAggregate
{
    /**
     * @var Collection
     */
    private $data;

    /**
     * @var Collection
     */
    public Collection $collection;

    public static function collect(?iterable $input = []): self
    {
        $input ??= [];
        return new static($input);
    }

    final  private function __construct(iterable $input)
    {
        $this->assertType($input);

        if ($input instanceof Collection) {
            $this->data = $input;
            return;
        }
        if (is_object($input)) {
            $input = iterator_to_array($input);
        }
        $this->data = new ArrayCollection($input);
    }

    /**
     * @param iterable $input
     */
    protected function assertType(iterable $input): void
    {
        $typeName = $this->typeName();
        foreach ($input as $item) {
            if (!is_a($item, $typeName)) {
                throw InvalidTypeException::make($item, $typeName);
            }
        }
    }

    abstract protected function typeName(): string;

    public function forRemovedItems(EntityList $list, callable $callback = null): self
    {
        $callback ??= [$this, 'remove'];
        $this->values()->diffUsing($list->values(), function (Comparable $left, Comparable $right) {
            return $left->compareTo($right);

        })->each(function (Comparable $element) use ($callback) {
            $callback($element);
        });

        return $this;
    }

    public function forAddedItems(EntityList $list, callable $callback = null): self
    {
        $callback ??= [$this, 'add'];
        $list->values()->diffUsing($this->values(), function (Comparable $left, Comparable $right) {
            return $left->compareTo($right);
        })->each(function (Comparable $element) use ($callback) {
            $callback($element);
        });

        return $this;
    }

    public function remove(Comparable $entity, ?callable $callback = null): self
    {
        $key = $this->indexOf($entity);

        if (false === $key) {
            return $this;
        }
        $element = $this->data->get($key);


        if (is_null($element)) {
            return $this;
        }

        $this->data->removeElement($element);
        $this->values()->forget($key);

        if (is_callable($callback)) {
            $callback($element);
        }

        return $this;
    }

    public function add(Comparable $entity, ?callable $callback = null): self
    {

        $key = $this->indexOf($entity);
        if (false !== $key) {
            return $this;
        }

        $this->data->add($entity);
        $this->values()->add($entity);

        if (is_callable($callback)) {
            $callback($entity);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function indexOf(Comparable $element)
    {
        return $this->values()->search(fn(Comparable $item) => $item->equals($element));
    }


    /**
     * @return \Tightenco\Collect\Support\Collection
     */
    public function values(): \Tightenco\Collect\Support\Collection
    {
        return collect($this->data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->data->count();
    }

    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data
            ->toArray();
    }

    public function toCollection(): Collection
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->values();
    }


}
