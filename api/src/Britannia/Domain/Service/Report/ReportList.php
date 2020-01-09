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

namespace Britannia\Domain\Service\Report;


use ArrayIterator;
use Cocur\Slugify\Slugify;
use Countable;
use IteratorAggregate;

final class ReportList implements IteratorAggregate, Countable
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var ReportInterface
     */
    private iterable $reports;

    public static function make(string $name, iterable $input): self
    {
        return new self($name, ...$input);
    }

    private function __construct(string $name, ReportInterface ...$reports)
    {
        $this->name = Slugify::create()->slugify($name);
        $this->reports = $reports;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    public function first(): ReportInterface
    {
        return array_shift($this->reports);
    }

    /**
     * @return ReportInterface[]
     */
    public function getIterator()
    {
        return new ArrayIterator($this->reports);
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        return count($this->reports);
    }


}
