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


use Countable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Behaviour\Comparable;

abstract class EntityList implements \IteratorAggregate, Countable
{
    /**
     * @var \Tightenco\Collect\Support\Collection
     */
    private $data;

    /**
     * @var Collection
     */
    public Collection $collection;

    public static function collect(iterable $items, ?EntityList $parent = null): self
    {

        $collection = self::getCollection($items, $parent);

        $data = collect($items)
            ->filter()
            ->values();


        return (new static(...$data))->setCollection($collection);
    }

    /**
     * @param iterable $items
     * @param EntityList|null $parent
     * @return Collection
     */
    private static function getCollection(iterable $items, ?EntityList $parent): Collection
    {
        if (!is_null($parent)) {
            return $parent->collection;
        }

        if ($items instanceof Collection) {
            return $items;
        }

        $data = collect($items)
            ->filter()
            ->values();

        return new ArrayCollection([...$data]);
    }


    private function setCollection(Collection $items): self
    {
        $this->collection = $items;
        return $this;
    }

    protected function __construct(iterable $items)
    {
        $this->data = collect($items);
    }


    public function forRemovedItems(EntityList $list, callable $callback = null): self
    {
        $callback ??= [$this, 'remove'];
        $this->data->diffUsing($list->data, function (Comparable $left, Comparable $right) {
            return $left->compareTo($right);

        })->each(function (Comparable $element) use ($callback) {
            $callback($element);

        });

        return $this;
    }

    public function forAddedItems(EntityList $list, callable $callback = null): self
    {
        $callback ??= [$this, 'add'];
        $list->data->diffUsing($this->data, function (Comparable $left, Comparable $right) {
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

        $element = $this->collection->get($key);
        if (is_null($element)) {
            return $this;
        }

        $this->collection->removeElement($element);
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

        $this->collection->add($entity);
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
        return $this->data->search(fn(Comparable $item) => $item->equals($element));
    }

    /**
     * @return \Tightenco\Collect\Support\Collection
     */
    public function data(): \Tightenco\Collect\Support\Collection
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return $this->data;
    }

    public function count()
    {
        return $this->collection->count();
    }

}
