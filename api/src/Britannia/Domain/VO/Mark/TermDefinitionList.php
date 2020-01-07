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

namespace Britannia\Domain\VO\Mark;


use ArrayIterator;
use IteratorAggregate;
use PlanB\DDD\Domain\VO\Percent;

final class TermDefinitionList implements IteratorAggregate
{
    /**
     * @var array|TermDefinition[]
     */
    private array $definitions;

    private $numOfUnits;

    public static function byDefault(): array
    {
        $input = [];
        foreach (TermName::all() as $term) {
            $key = (string)$term;
            $input[$key] = TermDefinition::make($term, Percent::make(30), NumOfUnits::ZERO(), NumOfUnits::ZERO());
        }

        return $input;

    }

    public static function collect(iterable $definitions): self
    {
        $values = collect($definitions)->values()->toArray();
        return new self(...$values);
    }

    protected function __construct(TermDefinition ...$definitions)
    {

        $this->numOfUnits = 0;
        foreach ($definitions as $definition) {
            $key = (string)$definition->termName();
            $this->definitions[$key] = $definition;

            $this->numOfUnits += $definition->numOfUnits()->getValue();
        }
    }

    public function numOfUnits(): int
    {
        return $this->numOfUnits;
    }

    public function definitionByTerm(TermName $term): TermDefinition
    {
        $key = (string)$term;
        return $this->definitions[$key];
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->definitions);
    }

}
