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

final class TermDefinitionList implements IteratorAggregate
{
    /**
     * @var array|TermDefinition[]
     */
    private array $definitions;

    public static function collect(iterable $definitions): self
    {
        $values = collect($definitions)->values()->toArray();
        return new self(...$values);
    }

    protected function __construct(TermDefinition ...$definitions)
    {
        $this->definitions = $definitions;
    }


    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        $data = [];
        foreach ($this->definitions as $definition) {
            $key = (string)$definition->term();
            $data[$key] = $definition;
        }

        return new ArrayIterator($data);
    }
}
