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

namespace Britannia\Domain\VO\Assessment;


use ArrayIterator;
use IteratorAggregate;

final class SkillList implements IteratorAggregate
{
    /**
     * @var MarkReport[]
     */
    private array $extraSkills;

    public static function fromNameList(string ...$names)
    {
        $input = collect($names)
            ->map(fn(string $name) => Skill::byName($name))
            ->toArray();

        return new self(...$input);
    }

    public static function collect(iterable $extraSkills = []): self
    {
        return new self(...$extraSkills);
    }

    private function __construct(Skill ...$extraSkills)
    {
        $this->extraSkills = $extraSkills;
    }

    public function toNamesList()
    {
        return collect($this->extraSkills)
            ->map(fn(Skill $skill) => $skill->getName())
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->extraSkills);
    }
}
